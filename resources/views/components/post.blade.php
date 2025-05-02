<div class="card post-card" style="width: {{ $attributes->get('width', 50) }}%; margin-bottom: 20px;">
    <div class="d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center" style="margin: 10px;">
            <a href="{{ route('perfil.show', $post->usuario->id) }}">
                <img src="{{ $post->usuario && $post->usuario->avatar ? asset('storage/' . $post->usuario->avatar) : asset('images/PerfilPredeterminado.jpg') }}"
                    style="width: 75px; border-radius: 50%; margin-right: 10px;"
                    alt="Imagen de perfil">
            </a>
            <p class="mb-0">
                {{ $post->usuario ? $post->usuario->nombre : 'Usuario desconocido' }}
            </p>
        </div>
        <div class="dropdown">
            <button class="btn btn-link p-0 text-dark" type="button" id="dropdownMenuButton{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('images/3puntos.png') }}" alt="3puntos" style="width: 25px;">
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $post->id }}">
                <li>
                    <a class="dropdown-item" href="{{ route('perfil.show', $post->usuario->id) }}">Ver perfil</a>
                </li>
                @if(Auth::id() !== $post->usuario_id)
                    <li>
                        <button class="dropdown-item follow-button" 
                                data-usuario-id="{{ $post->usuario->id }}" 
                                data-following="{{ Auth::user()->siguiendo->contains($post->usuario->id) ? 'true' : 'false' }}">
                            {{ Auth::user()->siguiendo->contains($post->usuario->id) ? 'Parar de seguir' : 'Seguir' }}
                        </button>
                    </li>
                @endif
                @if(Auth::id() === $post->usuario_id || Auth::user()->rol === 'admin')
                    <li>
                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPostModal{{ $post->id }}">Editar</button>
                    </li>
                    <li>
                        <button class="dropdown-item" onclick="confirmDelete('{{ route('admin.publicaciones.eliminar', $post->id) }}')">Eliminar</button>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card-body" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
        <h5 class="card-title">{{ $post->titulo }}</h5>
        <p class="card-text" style="white-space: pre-wrap; overflow: hidden; max-height: 100px;">{{ Str::limit($post->contenido, 150) }}</p>
    </div>
    @if($post->imagen)
        <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
            <img src="{{ asset('storage/' . $post->imagen) }}"
                class="card-img-bottom"
                style="max-height: 300px; max-width: 100%; min-height: 100px; min-width: 100px; object-fit: contain;"
                alt="Imagen de {{ $post->titulo }}">
        </div>
    @endif
    <div class="card-footer">
        <div class="botones">
            <div>
                <img src="{{ asset('images/Comentarios.png') }}" style="width: 25px;" alt="Comentarios">
                <span class="ms-2">{{ $post->comentarios->count() }}</span>
                <button class="btn btn-link p-0 like-button" data-publicacion-id="{{ $post->id }}">
                    <img src="{{ Auth::user()->likes->contains('publicacion_id', $post->id) ? asset('images/LikeDado.png') : asset('images/Like.png') }}"
                        style="width: 25px;"
                        alt="Me gusta">
                </button>
                <span class="like-count" data-publicacion-id="{{ $post->id }}">{{ $post->likes->count() }} Me gusta</span>
            </div>
        </div>
        <form class="comentario-form" data-publicacion-id="{{ $post->id }}" style="margin-top: 10px;">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control comentario-input" placeholder="Escribe un comentario..." required>
                <button class="btn btn-primary" type="submit">Comentar</button>
            </div>
        </form>
        <div class="comentarios-list mb-3" data-publicacion-id="{{ $post->id }}" style="margin-bottom: 20px;">
            @if($post->comentarios->count() > 0)
                @foreach($post->comentarios->take(3) as $comentario)
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
                @if($post->comentarios->count() > 3)
                    <p>...</p>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Contenido copiado al portapapeles.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    </div>
</div>

<div id="customConfirm" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1060; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <p>¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.</p>
    <div class="d-flex justify-content-end">
        <button id="cancelDelete" class="btn btn-secondary me-2">Cancelar</button>
        <button id="confirmDeleteButton" class="btn btn-danger">Eliminar</button>
    </div>
</div>

<!-- Modal de edición -->
@include('components.modals.edit-post', ['post' => $post])

<!-- Modal de visualización -->
@include('components.modals.ver-post', ['post' => $post])