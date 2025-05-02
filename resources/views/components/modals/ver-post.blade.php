<div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1" aria-labelledby="postModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel{{ $post->id }}">{{ $post->titulo }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p style="white-space: pre-wrap;">{{ $post->contenido }}</p>
                @if($post->imagen)
                    <img src="{{ asset('storage/' . $post->imagen) }}" class="img-fluid mb-3" alt="Imagen de {{ $post->titulo }}">
                @endif
                <form class="comentario-form" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control comentario-input" placeholder="Escribe un comentario..." required>
                        <button class="btn btn-primary" type="submit">Comentar</button>
                    </div>
                </form>
                <div class="comentarios-list-modal" data-publicacion-id="{{ $post->id }}">
                    @foreach($post->comentarios as $comentario)
                    <hr>
                    <div class="comentario">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <!-- Imagen del usuario -->
                                <a href="{{ route('perfil.show', $comentario->usuario->id) }}" class="me-2">
                                    <img src="{{ $comentario->usuario->avatar ? asset('storage/' . $comentario->usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}"
                                        alt="Imagen de {{ $comentario->usuario->nombre }}"
                                        style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                </a>
                                <!-- Nombre y contenido del comentario -->
                                <strong>{{ $comentario->usuario->nombre }}:</strong> {{ $comentario->contenido }}
                            </div>
                            <!-- Menú desplegable -->
                            <div class="dropdown">
                                <button class="btn btn-link p-0 text-dark" type="button" id="dropdownMenuButtonComentario{{ $comentario->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('images/3puntos.png') }}" alt="3puntos" style="width: 20px;">
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="dropdownMenuButtonComentario{{ $comentario->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('perfil.show', $comentario->usuario->id) }}">Ver perfil</a>
                                    </li>
                                    @if(Auth::id() === $comentario->usuario_id || Auth::user()->rol === 'admin')
                                        <li>
                                            <button class="dropdown-item" onclick="confirmDelete('{{ route('admin.comentarios.eliminar', $comentario->id) }}')">Eliminar</button>
                                        </li>
                                    @endif
                                    <li>
                                        <button class="dropdown-item" onclick="copiarContenido('{{ $comentario->contenido }}')">Copiar contenido</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>