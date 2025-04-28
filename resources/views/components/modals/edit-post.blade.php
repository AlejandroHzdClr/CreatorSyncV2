<div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel{{ $post->id }}">Editar Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.publicaciones.actualizar', $post->id) }}" method="POST" enctype="multipart/form-data" id="editPostForm{{ $post->id }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="titulo{{ $post->id }}" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo{{ $post->id }}" name="titulo" value="{{ $post->titulo }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="contenido{{ $post->id }}" class="form-label">Contenido</label>
                        <textarea class="form-control" id="contenido{{ $post->id }}" name="contenido" rows="5" required>{{ $post->contenido }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen{{ $post->id }}" class="form-label">Imagen (Opcional)</label>
                        <input type="file" class="form-control" id="imagen{{ $post->id }}" name="imagen" accept="image/*">
                        @if($post->imagen)
                            <img src="{{ asset('storage/' . $post->imagen) }}" alt="Imagen actual" class="img-fluid mt-2" style="max-height: 200px;">
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editPostForm{{ $post->id }}" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>