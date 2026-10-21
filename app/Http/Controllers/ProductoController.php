<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $query = Producto::with('categoria');

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($consulta) use ($search) {
                $consulta->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('descripcion', 'like', '%' . $search . '%');
            });
        }

        $productos = $query->orderBy('precio', 'asc')
            ->paginate(10)
            ->withQueryString();
        return view('products.index', [
            'categorias' => $categorias,
            'productos' => $productos,
        ]);
    }

    public function create()
    {
        $categorias = Categoria::all();

        return view('products.create', [
            'categorias' => $categorias,
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Producto::create($datos);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Producto $producto)
    {
    $categorias = Categoria::all();

    return view('products.edit', [
        'categorias' => $categorias,
        'producto' => $producto,
    ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $producto->update($datos);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
