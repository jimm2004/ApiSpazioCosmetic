<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AdminProductoController extends Controller
{
    public function listarProductosConImagen()
    {
        $productos = Producto::query()
            ->from('productos as p')
            ->leftJoin('categorias as c', 'p.id_categoria', '=', 'c.id_categoria')
            ->leftJoin('inventario as i', 'p.id_producto', '=', 'i.producto_id')
            ->orderBy('p.nombre', 'asc')
            ->select([
                'p.id_producto',
                'p.nombre',
                'p.descripcion',
                'p.precio_venta',
                'p.id_categoria',
                'c.nombre_categoria',
                'c.descripcion as categoria_descripcion',
                'i.cantidad_stock',
                'i.fecha_actualizacion',
            ])
            ->get();

        $imagenes = ProductoImagen::query()
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('producto_master_id');

        $data = $productos->map(function ($producto) use ($imagenes) {
            $fotos = $imagenes->get($producto->id_producto, collect());

            $imagenPrincipal = $this->obtenerImagenPrincipal($fotos);
            $precioVenta = (float) ($producto->precio_venta ?? 0);
            $precioFinal = $this->obtenerPrecioFinalProducto($fotos, $precioVenta);

            return [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion ?? '',
                'precio_venta' => $precioVenta,
                'precio_final' => $precioFinal,

                'categoria' => $producto->id_categoria ? [
                    'id_categoria' => $producto->id_categoria,
                    'nombre_categoria' => $producto->nombre_categoria,
                    'descripcion' => $producto->categoria_descripcion,
                ] : null,

                'inventario' => [
                    'cantidad_stock' => $producto->cantidad_stock ?? 0,
                    'fecha_actualizacion' => $producto->fecha_actualizacion,
                ],

                'imagen' => $imagenPrincipal?->imagen,
                'imagen_url' => $imagenPrincipal?->imagen_url,
                'imagen_principal' => $imagenPrincipal
                    ? $this->formatearImagen($imagenPrincipal)
                    : null,

                'imagenes' => $fotos->map(function ($foto) {
                    return $this->formatearImagen($foto);
                })->values(),

                'total_imagenes' => $fotos->count(),

                'activo' => $fotos->count() > 0
                    ? $fotos->contains(fn ($foto) => (bool) $foto->activo)
                    : true,
            ];
        });

        return response()->json([
            'ok' => true,
            'message' => 'Productos con imágenes consultados correctamente',
            'total' => $data->count(),
            'data' => $data,
        ], 200);
    }

    public function detalleProducto($id)
    {
        $producto = Producto::query()
            ->from('productos as p')
            ->leftJoin('categorias as c', 'p.id_categoria', '=', 'c.id_categoria')
            ->leftJoin('inventario as i', 'p.id_producto', '=', 'i.producto_id')
            ->where('p.id_producto', $id)
            ->select([
                'p.id_producto',
                'p.nombre',
                'p.descripcion',
                'p.precio_venta',
                'p.id_categoria',
                'c.nombre_categoria',
                'c.descripcion as categoria_descripcion',
                'i.cantidad_stock',
                'i.fecha_actualizacion',
            ])
            ->first();

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $fotos = ProductoImagen::query()
            ->where('producto_master_id', $producto->id_producto)
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $imagenPrincipal = $this->obtenerImagenPrincipal($fotos);
        $precioVenta = (float) ($producto->precio_venta ?? 0);
        $precioFinal = $this->obtenerPrecioFinalProducto($fotos, $precioVenta);

        return response()->json([
            'ok' => true,
            'data' => [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion ?? '',
                'precio_venta' => $precioVenta,
                'precio_final' => $precioFinal,

                'categoria' => $producto->id_categoria ? [
                    'id_categoria' => $producto->id_categoria,
                    'nombre_categoria' => $producto->nombre_categoria,
                    'descripcion' => $producto->categoria_descripcion,
                ] : null,

                'inventario' => [
                    'cantidad_stock' => $producto->cantidad_stock ?? 0,
                    'fecha_actualizacion' => $producto->fecha_actualizacion,
                ],

                'imagen' => $imagenPrincipal?->imagen,
                'imagen_url' => $imagenPrincipal?->imagen_url,
                'imagen_principal' => $imagenPrincipal
                    ? $this->formatearImagen($imagenPrincipal)
                    : null,

                'imagenes' => $fotos->map(function ($foto) {
                    return $this->formatearImagen($foto);
                })->values(),

                'total_imagenes' => $fotos->count(),

                'activo' => $fotos->count() > 0
                    ? $fotos->contains(fn ($foto) => (bool) $foto->activo)
                    : true,
            ]
        ], 200);
    }

    public function subirImagenProducto(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio_final' => 'nullable|numeric|min:0',
            'es_principal' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Archivo inválido',
                'errors' => $validator->errors(),
            ], 422);
        }

        $totalImagenes = ProductoImagen::where('producto_master_id', $producto->id_producto)
            ->count();

        if ($totalImagenes >= 2) {
            return response()->json([
                'ok' => false,
                'message' => 'Este producto ya tiene el máximo permitido de 2 imágenes. Selecciona una ranura ocupada para cambiarla.',
            ], 422);
        }

        $file = $request->file('imagen');

        if (! $file || ! $file->isValid()) {
            return response()->json([
                'ok' => false,
                'message' => 'La imagen no llegó correctamente al servidor.',
            ], 422);
        }

        try {
            $carpetaDestino = $this->carpetaProductosHostinger();

            if (! File::exists($carpetaDestino)) {
                File::makeDirectory($carpetaDestino, 0775, true);
            }

            if (! is_writable($carpetaDestino)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La carpeta productos no tiene permisos de escritura.',
                    'debug' => [
                        'carpetaDestino' => $carpetaDestino,
                        'existe' => File::exists($carpetaDestino),
                        'is_writable' => is_writable($carpetaDestino),
                        'public_path' => public_path(),
                        'dirname_public_path' => dirname(public_path()),
                    ],
                ], 500);
            }

            $extension = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $nombreArchivo = $this->crearNombreArchivo(
                $producto->id_producto,
                $producto->nombre,
                $extension
            );

            $file->move($carpetaDestino, $nombreArchivo);

            $rutaFisica = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;

            if (! File::exists($rutaFisica)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La imagen no se guardó físicamente en el host.',
                    'debug' => [
                        'carpetaDestino' => $carpetaDestino,
                        'rutaFisica' => $rutaFisica,
                        'public_path' => public_path(),
                        'dirname_public_path' => dirname(public_path()),
                    ],
                ], 500);
            }

            $path = 'productos/' . $nombreArchivo;
            $imageUrl = $this->urlPublicaImagen($request, $path);

            $precioVenta = (float) ($producto->precio_venta ?? 0);

            $precioFinal = $request->filled('precio_final')
                ? (float) $request->precio_final
                : $this->obtenerPrecioFinalActualProducto($producto->id_producto, $precioVenta);

            $esPrincipal = $totalImagenes === 0
                ? true
                : $request->boolean('es_principal');

            $registro = DB::transaction(function () use (
                $producto,
                $path,
                $imageUrl,
                $precioVenta,
                $precioFinal,
                $esPrincipal,
                $totalImagenes
            ) {
                if ($esPrincipal) {
                    ProductoImagen::where('producto_master_id', $producto->id_producto)
                        ->update([
                            'es_principal' => 0,
                            'updated_at' => now(),
                        ]);
                }

                $registro = ProductoImagen::create([
                    'producto_master_id' => $producto->id_producto,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion ?? '',
                    'precio_venta' => $precioVenta,
                    'precio_final' => $precioFinal,
                    'imagen' => $path,
                    'imagen_url' => $imageUrl,
                    'activo' => 1,
                    'es_principal' => $esPrincipal ? 1 : 0,
                    'orden' => $totalImagenes + 1,
                ]);

                // Regla de negocio:
                // 1 producto = 1 precio final, aunque tenga 2 imágenes.
                ProductoImagen::where('producto_master_id', $producto->id_producto)
                    ->update([
                        'precio_final' => $precioFinal,
                        'updated_at' => now(),
                    ]);

                return $registro->fresh();
            });

            return response()->json([
                'ok' => true,
                'message' => 'Imagen guardada correctamente',
                'data' => $this->formatearImagen($registro),
                'debug' => [
                    'ruta_fisica' => $rutaFisica,
                    'url_publica' => $imageUrl,
                    'public_path' => public_path(),
                    'dirname_public_path' => dirname(public_path()),
                ],
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al guardar la imagen en el servidor.',
                'error' => $e->getMessage(),
                'debug' => [
                    'public_path' => public_path(),
                    'dirname_public_path' => dirname(public_path()),
                ],
            ], 500);
        }
    }

    public function actualizarPrecioFinalImagen(Request $request, $id)
    {
        $imagen = ProductoImagen::find($id);

        if (! $imagen) {
            return response()->json([
                'ok' => false,
                'message' => 'Imagen del producto no encontrada.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'precio_final' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Precio final inválido.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $precioFinal = (float) $request->precio_final;

        ProductoImagen::where('producto_master_id', $imagen->producto_master_id)
            ->update([
                'precio_final' => $precioFinal,
                'updated_at' => now(),
            ]);

        $imagenesActualizadas = ProductoImagen::where('producto_master_id', $imagen->producto_master_id)
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'ok' => true,
            'message' => 'Precio final actualizado para todas las imágenes del producto.',
            'data' => [
                'producto_master_id' => $imagen->producto_master_id,
                'precio_final' => $precioFinal,
                'imagenes' => $imagenesActualizadas->map(function ($foto) {
                    return $this->formatearImagen($foto);
                })->values(),
            ],
        ], 200);
    }

    public function cambiarImagenProducto(Request $request, $id)
    {
        $imagen = ProductoImagen::find($id);

        if (! $imagen) {
            return response()->json([
                'ok' => false,
                'message' => 'Imagen del producto no encontrada.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio_final' => 'nullable|numeric|min:0',
            'es_principal' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Archivo inválido.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('imagen');

        if (! $file || ! $file->isValid()) {
            return response()->json([
                'ok' => false,
                'message' => 'La nueva imagen no llegó correctamente al servidor.',
            ], 422);
        }

        try {
            $carpetaDestino = $this->carpetaProductosHostinger();

            if (! File::exists($carpetaDestino)) {
                File::makeDirectory($carpetaDestino, 0775, true);
            }

            if (! is_writable($carpetaDestino)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La carpeta productos no tiene permisos de escritura.',
                    'debug' => [
                        'carpetaDestino' => $carpetaDestino,
                        'existe' => File::exists($carpetaDestino),
                        'is_writable' => is_writable($carpetaDestino),
                    ],
                ], 500);
            }

            $archivoAnterior = ! empty($imagen->imagen)
                ? basename($imagen->imagen)
                : null;

            $rutaAnterior = $archivoAnterior
                ? $carpetaDestino . DIRECTORY_SEPARATOR . $archivoAnterior
                : null;

            $extension = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $nombreArchivo = $this->crearNombreArchivo(
                $imagen->producto_master_id,
                $imagen->nombre ?? 'producto',
                $extension
            );

            $file->move($carpetaDestino, $nombreArchivo);

            $rutaFisica = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;

            if (! File::exists($rutaFisica)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La nueva imagen no se guardó físicamente en el host.',
                    'debug' => [
                        'carpetaDestino' => $carpetaDestino,
                        'rutaFisica' => $rutaFisica,
                    ],
                ], 500);
            }

            $path = 'productos/' . $nombreArchivo;
            $imageUrl = $this->urlPublicaImagen($request, $path);

            $esPrincipal = $request->has('es_principal')
                ? $request->boolean('es_principal')
                : (bool) $imagen->es_principal;

            $precioFinalNuevo = $request->filled('precio_final')
                ? (float) $request->precio_final
                : null;

            DB::transaction(function () use (
                $imagen,
                $path,
                $imageUrl,
                $esPrincipal,
                $precioFinalNuevo
            ) {
                if ($esPrincipal) {
                    ProductoImagen::where('producto_master_id', $imagen->producto_master_id)
                        ->where('id', '!=', $imagen->id)
                        ->update([
                            'es_principal' => 0,
                            'updated_at' => now(),
                        ]);
                }

                $imagen->imagen = $path;
                $imagen->imagen_url = $imageUrl;
                $imagen->es_principal = $esPrincipal ? 1 : 0;
                $imagen->activo = 1;
                $imagen->updated_at = now();

                if ($precioFinalNuevo !== null) {
                    $imagen->precio_final = $precioFinalNuevo;
                }

                $imagen->save();

                // Regla de negocio:
                // si se cambia precio en una imagen, se sincroniza en todas.
                if ($precioFinalNuevo !== null) {
                    ProductoImagen::where('producto_master_id', $imagen->producto_master_id)
                        ->update([
                            'precio_final' => $precioFinalNuevo,
                            'updated_at' => now(),
                        ]);
                }
            });

            // Eliminar archivo anterior solo después de guardar la nueva imagen
            // y actualizar la BD correctamente.
            if ($rutaAnterior && File::exists($rutaAnterior)) {
                File::delete($rutaAnterior);
            }

            $imagen->refresh();

            return response()->json([
                'ok' => true,
                'message' => 'Imagen reemplazada correctamente.',
                'data' => $this->formatearImagen($imagen),
                'debug' => [
                    'ruta_fisica' => $rutaFisica,
                    'url_publica' => $imageUrl,
                ],
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al reemplazar la imagen.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cambiarVisibilidad(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Dato inválido, se esperaba un booleano.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $esActivo = $request->boolean('activo');

        ProductoImagen::where('producto_master_id', $producto->id_producto)
            ->update([
                'activo' => $esActivo,
                'updated_at' => now(),
            ]);

        return response()->json([
            'ok' => true,
            'message' => $esActivo
                ? 'Producto visible en el catálogo'
                : 'Producto oculto exitosamente',
            'data' => [
                'producto_master_id' => $producto->id_producto,
                'activo' => $esActivo,
            ]
        ], 200);
    }

    private function carpetaProductosHostinger()
    {
        return dirname(public_path())
            . DIRECTORY_SEPARATOR . 'public_html'
            . DIRECTORY_SEPARATOR . 'productos';
    }

    private function crearNombreArchivo($productoId, $nombre, $extension)
    {
        $nombreLimpio = Str::slug($nombre ?: 'producto', '_');

        return now()->format('YmdHis')
            . '_producto_' . $productoId
            . '_' . $nombreLimpio
            . '_' . Str::random(8)
            . '.' . $extension;
    }

    private function urlPublicaImagen(Request $request, $path)
    {
        $baseUrl = rtrim(
            config('app.url') ?: $request->getSchemeAndHttpHost(),
            '/'
        );

        return $baseUrl . '/' . ltrim($path, '/');
    }

    private function obtenerImagenPrincipal($fotos)
    {
        return $fotos->firstWhere('es_principal', 1)
            ?? $fotos->firstWhere('es_principal', true)
            ?? $fotos->first();
    }

    private function obtenerPrecioFinalProducto($fotos, $precioVenta)
    {
        $foto = $fotos->first(function ($item) {
            return $item->precio_final !== null;
        });

        return $foto
            ? (float) ($foto->precio_final ?? $foto->precio_venta ?? $precioVenta)
            : (float) $precioVenta;
    }

    private function obtenerPrecioFinalActualProducto($productoMasterId, $precioVenta)
    {
        $foto = ProductoImagen::where('producto_master_id', $productoMasterId)
            ->whereNotNull('precio_final')
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return $foto
            ? (float) ($foto->precio_final ?? $foto->precio_venta ?? $precioVenta)
            : (float) $precioVenta;
    }

    private function formatearImagen($foto)
    {
        return [
            'id' => $foto->id,
            'producto_master_id' => $foto->producto_master_id,
            'nombre' => $foto->nombre,
            'descripcion' => $foto->descripcion,
            'precio_venta' => (float) ($foto->precio_venta ?? 0),
            'precio_final' => (float) ($foto->precio_final ?? $foto->precio_venta ?? 0),
            'imagen' => $foto->imagen,
            'imagen_url' => $foto->imagen_url,
            'es_principal' => (bool) ($foto->es_principal ?? false),
            'orden' => (int) ($foto->orden ?? 0),
            'activo' => (bool) $foto->activo,
        ];
    }
}