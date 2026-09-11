{{-- resources/views/public/partials/media-carousel.blade.php --}}
@if($medias->count() > 0)
    @php
        $isLarge = $large ?? false;
        $aspectClass = $isLarge ? 'aspect-[4/3]' : 'aspect-square';
        $uniqueId = 'carousel-' . $carouselId;
        
        $mediasJson = $medias->map(function($m) {
            return [
                'src' => asset('storage/' . $m->chemin_fichier),
                'thumb' => asset('storage/' . ($m->thumbnail ?? $m->chemin_fichier)),
                'title' => $m->titre ?? '',
            ];
        })->values()->toJson();
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-ink-200 overflow-hidden">
        <div class="relative">
            {{-- Slides --}}
            <div id="{{ $uniqueId }}" 
                 class="relative {{ $aspectClass }} bg-white select-none"
                 data-carousel-medias="{{ $mediasJson }}">
                @foreach($medias as $index => $media)
                    <div class="carousel-slide absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                         data-index="{{ $index }}">
                        
                        <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                             alt="{{ $media->titre }}"
                             data-lightbox-trigger
                             data-lightbox-carousel="{{ $carouselId }}"
                             data-lightbox-index="{{ $index }}"
                             class="w-full h-full object-contain cursor-zoom-in hover:opacity-95 transition"
                             draggable="false">
                        
                        @if($media->titre)
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 pointer-events-none">
                                <p class="text-white text-sm font-medium truncate">{{ $media->titre }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($medias->count() > 1)
                <button type="button" 
                        onclick="carouselPrev('{{ $carouselId }}')"
                        class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full shadow-lg items-center justify-center hover:bg-white transition z-10">
                    <svg class="w-4 h-4 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button type="button" 
                        onclick="carouselNext('{{ $carouselId }}')"
                        class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full shadow-lg items-center justify-center hover:bg-white transition z-10">
                    <svg class="w-4 h-4 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div class="absolute top-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded backdrop-blur-sm z-10 pointer-events-none">
                    <span class="carousel-counter-{{ $carouselId }}">1</span> / {{ $medias->count() }}
                </div>
            @endif
        </div>

        {{-- Miniatures --}}
        @if($medias->count() > 1)
            <div class="flex gap-2 p-3 bg-ink-50 border-t border-ink-100 overflow-x-auto">
                @foreach($medias as $index => $media)
                    <button type="button" 
                            onclick="carouselGoTo('{{ $carouselId }}', {{ $index }})"
                            class="carousel-thumb-{{ $carouselId }} flex-shrink-0 w-16 h-16 rounded border-2 {{ $index === 0 ? 'border-amber-500' : 'border-transparent' }} overflow-hidden hover:border-amber-300 transition bg-white"
                            data-index="{{ $index }}">
                        <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                             alt="{{ $media->titre }}"
                             class="w-full h-full object-contain">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        (function() {
            const carouselId = '{{ $carouselId }}';
            const totalSlides = {{ $medias->count() }};
            let currentIndex = 0;

            window[`carouselGoTo_${carouselId}`] = function(index) {
                const slides = document.querySelectorAll(`#carousel-${carouselId} .carousel-slide`);
                const thumbs = document.querySelectorAll(`.carousel-thumb-${carouselId}`);
                const counter = document.querySelector(`.carousel-counter-${carouselId}`);
                
                slides.forEach((slide, i) => {
                    slide.classList.toggle('opacity-100', i === index);
                    slide.classList.toggle('opacity-0', i !== index);
                    slide.classList.toggle('pointer-events-none', i !== index);
                });
                
                thumbs.forEach((thumb, i) => {
                    thumb.classList.toggle('border-amber-500', i === index);
                    thumb.classList.toggle('border-transparent', i !== index);
                });

                if (counter) counter.textContent = index + 1;
                currentIndex = index;
            };

            window[`carouselNext_${carouselId}`] = function() {
                const next = (currentIndex + 1) % totalSlides;
                window[`carouselGoTo_${carouselId}`](next);
            };

            window[`carouselPrev_${carouselId}`] = function() {
                const prev = (currentIndex - 1 + totalSlides) % totalSlides;
                window[`carouselGoTo_${carouselId}`](prev);
            };

            // Swipe sur le carrousel (mobile)
            const carouselEl = document.getElementById(`carousel-${carouselId}`);
            if (carouselEl) {
                let touchStartX = 0;
                let touchEndX = 0;

                carouselEl.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                carouselEl.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    const delta = touchEndX - touchStartX;
                    
                    if (Math.abs(delta) > 50) {
                        if (delta > 0) {
                            window[`carouselPrev_${carouselId}`]();
                        } else {
                            window[`carouselNext_${carouselId}`]();
                        }
                    }
                }, { passive: true });
            }
        })();

        function carouselGoTo(id, index) {
            if (window[`carouselGoTo_${id}`]) window[`carouselGoTo_${id}`](index);
        }
        function carouselNext(id) {
            if (window[`carouselNext_${id}`]) window[`carouselNext_${id}`]();
        }
        function carouselPrev(id) {
            if (window[`carouselPrev_${id}`]) window[`carouselPrev_${id}`]();
        }
    </script>
    @endpush

    {{-- ============================================================ --}}
    {{-- LIGHTBOX --}}
    {{-- ============================================================ --}}
    @once
        @push('styles')
        <style>
            #lightbox {
                touch-action: none;
            }
            #lightbox-img {
                transition: transform 0.2s ease-out;
                transform-origin: center center;
                max-width: 100%;
                max-height: 100%;
            }
            #lightbox-img.zoomed {
                cursor: grab;
            }
            #lightbox-img.dragging {
                cursor: grabbing;
                transition: none;
            }
            /* Fade out auto des contrôles */
            #lightbox-controls {
                transition: opacity 0.4s ease;
            }
            #lightbox-controls.hidden-fade {
                opacity: 0;
                pointer-events: none;
            }
        </style>
        @endpush

        @push('scripts')
        <div id="lightbox" 
             class="hidden fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center overflow-hidden"
             onclick="if(event.target === this) closeLightbox()">
            
            {{-- Zone de l'image --}}
            <div id="lightbox-stage" class="absolute inset-0 flex items-center justify-center">
                <img id="lightbox-img" 
                     src="" 
                     alt=""
                     class="max-w-[95vw] max-h-[90vh] object-contain select-none"
                     draggable="false">
            </div>

            {{-- Contrôles (fade out automatique) --}}
            <div id="lightbox-controls" class="absolute inset-0 pointer-events-none">
                
                {{-- Bouton fermer --}}
                <button type="button" 
                        onclick="closeLightbox()"
                        class="absolute top-4 right-4 w-11 h-11 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition z-20 pointer-events-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Compteur --}}
                <div class="absolute top-5 left-5 bg-white/10 backdrop-blur-sm text-white text-sm px-3 py-1.5 rounded-full z-20">
                    <span id="lightbox-current">1</span> / <span id="lightbox-total">1</span>
                </div>

                {{-- Indicateur de zoom --}}
                <div id="lightbox-zoom-indicator" 
                     class="absolute top-5 left-1/2 -translate-x-1/2 bg-white/10 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full z-20 opacity-0 transition-opacity">
                    <span id="lightbox-zoom-level">100%</span>
                </div>

                {{-- Flèche précédente --}}
                <button type="button" 
                        onclick="lightboxPrev()"
                        id="lightbox-prev"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition z-20 pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Flèche suivante --}}
                <button type="button" 
                        onclick="lightboxNext()"
                        id="lightbox-next"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition z-20 pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Miniatures (en bas) --}}
                <div id="lightbox-thumbs" 
                     class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 px-3 py-2 bg-black/50 backdrop-blur-sm rounded-lg max-w-[90vw] overflow-x-auto pointer-events-auto">
                    {{-- Rempli par JS --}}
                </div>

                {{-- Titre --}}
                <div id="lightbox-title" 
                     class="absolute bottom-24 left-1/2 -translate-x-1/2 bg-black/70 text-white text-sm px-4 py-2 rounded backdrop-blur-sm max-w-[90%] truncate">
                </div>

                {{-- Hint zoom --}}
                <div class="absolute bottom-4 right-4 text-white/50 text-xs hidden md:block">
                    Scroll pour zoomer · Esc pour fermer
                </div>
            </div>
        </div>
        @endpush
    @endonce

    @push('scripts')
    <script>
        // ============================================================
        // LIGHTBOX STATE
        // ============================================================
        let lightboxMedias = [];
        let lightboxCurrentIndex = 0;
        let lightboxZoom = 1;
        let lightboxPanX = 0;
        let lightboxPanY = 0;
        let lightboxDragging = false;
        let lightboxDragStartX = 0;
        let lightboxDragStartY = 0;
        let lightboxFadeTimeout = null;

        // ============================================================
        // INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-lightbox-trigger]').forEach(img => {
                img.addEventListener('click', function() {
                    const carouselId = this.dataset.lightboxCarousel;
                    const index = parseInt(this.dataset.lightboxIndex);
                    const carouselEl = document.querySelector(`#carousel-${carouselId}`);
                    
                    if (!carouselEl) return;

                    try {
                        lightboxMedias = JSON.parse(carouselEl.dataset.carouselMedias);
                    } catch (e) {
                        console.error('Erreur parsing médias:', e);
                        return;
                    }

                    if (lightboxMedias.length === 0) return;

                    openLightboxAt(index);
                });
            });

            initLightboxControls();
        });

        // ============================================================
        // OUVRIR
        // ============================================================
        function openLightboxAt(index) {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox) return;

            lightboxCurrentIndex = index;
            resetZoom();
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            updateLightbox();
            renderLightboxThumbs();
            showLightboxControls();
            scheduleControlsFade();
        }

        // ============================================================
        // METTRE À JOUR L'IMAGE
        // ============================================================
        function updateLightbox() {
            const img = document.getElementById('lightbox-img');
            const title = document.getElementById('lightbox-title');
            const current = document.getElementById('lightbox-current');
            const total = document.getElementById('lightbox-total');
            const prevBtn = document.getElementById('lightbox-prev');
            const nextBtn = document.getElementById('lightbox-next');

            const media = lightboxMedias[lightboxCurrentIndex];
            if (!media) return;

            img.src = media.src;
            img.alt = media.title || '';
            title.textContent = media.title || '';
            current.textContent = lightboxCurrentIndex + 1;
            total.textContent = lightboxMedias.length;

            if (lightboxMedias.length <= 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            }

            // Reset zoom à chaque changement
            resetZoom();
            updateLightboxThumbs();
        }

        // ============================================================
        // MINIATURES DU LIGHTBOX
        // ============================================================
        function renderLightboxThumbs() {
            const container = document.getElementById('lightbox-thumbs');
            if (!container) return;

            if (lightboxMedias.length <= 1) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'flex';
            container.innerHTML = '';

            lightboxMedias.forEach((media, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lightbox-thumb flex-shrink-0 w-12 h-12 rounded border-2 ${index === lightboxCurrentIndex ? 'border-amber-500' : 'border-transparent opacity-60'} overflow-hidden hover:opacity-100 transition`;
                btn.dataset.index = index;
                btn.innerHTML = `<img src="${media.thumb}" class="w-full h-full object-cover" draggable="false">`;
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    lightboxCurrentIndex = index;
                    updateLightbox();
                    renderLightboxThumbs();
                });
                container.appendChild(btn);
            });
        }

        function updateLightboxThumbs() {
            document.querySelectorAll('.lightbox-thumb').forEach((thumb, i) => {
                thumb.classList.toggle('border-amber-500', i === lightboxCurrentIndex);
                thumb.classList.toggle('border-transparent', i !== lightboxCurrentIndex);
                thumb.classList.toggle('opacity-60', i !== lightboxCurrentIndex);
            });
        }

        // ============================================================
        // NAVIGATION
        // ============================================================
        function lightboxNext() {
            if (lightboxMedias.length <= 1) return;
            lightboxCurrentIndex = (lightboxCurrentIndex + 1) % lightboxMedias.length;
            updateLightbox();
            renderLightboxThumbs();
        }

        function lightboxPrev() {
            if (lightboxMedias.length <= 1) return;
            lightboxCurrentIndex = (lightboxCurrentIndex - 1 + lightboxMedias.length) % lightboxMedias.length;
            updateLightbox();
            renderLightboxThumbs();
        }

        // ============================================================
        // FERMER
        // ============================================================
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox) return;
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
            lightboxMedias = [];
            lightboxCurrentIndex = 0;
            resetZoom();
        }

        // ============================================================
        // ZOOM (molette + pinch)
        // ============================================================
        function resetZoom() {
            lightboxZoom = 1;
            lightboxPanX = 0;
            lightboxPanY = 0;
            applyZoom();
        }

        function applyZoom() {
            const img = document.getElementById('lightbox-img');
            if (!img) return;
            img.style.transform = `translate(${lightboxPanX}px, ${lightboxPanY}px) scale(${lightboxZoom})`;
            img.classList.toggle('zoomed', lightboxZoom > 1);

            // Indicateur de zoom
            const indicator = document.getElementById('lightbox-zoom-indicator');
            const level = document.getElementById('lightbox-zoom-level');
            if (lightboxZoom > 1) {
                level.textContent = Math.round(lightboxZoom * 100) + '%';
                indicator.style.opacity = '1';
                clearTimeout(indicator._timeout);
                indicator._timeout = setTimeout(() => {
                    indicator.style.opacity = '0';
                }, 1500);
            } else {
                indicator.style.opacity = '0';
            }
        }

        function initLightboxControls() {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            const controls = document.getElementById('lightbox-controls');
            if (!lightbox || !img) return;

            // ============================================================
            // ZOOM AU SCROLL
            // ============================================================
            lightbox.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.15 : 0.15;
                lightboxZoom = Math.min(Math.max(0.5, lightboxZoom + delta), 5);
                
                if (lightboxZoom <= 1) {
                    lightboxPanX = 0;
                    lightboxPanY = 0;
                }
                
                applyZoom();
                showLightboxControls();
                scheduleControlsFade();
            }, { passive: false });

            // ============================================================
            // PINCH ZOOM (touch)
            // ============================================================
            let initialDistance = 0;
            let initialZoom = 1;

            lightbox.addEventListener('touchstart', (e) => {
                if (e.touches.length === 2) {
                    initialDistance = getTouchDistance(e.touches[0], e.touches[1]);
                    initialZoom = lightboxZoom;
                } else if (e.touches.length === 1 && lightboxZoom > 1) {
                    lightboxDragging = true;
                    lightboxDragStartX = e.touches[0].clientX - lightboxPanX;
                    lightboxDragStartY = e.touches[0].clientY - lightboxPanY;
                }
                showLightboxControls();
                scheduleControlsFade();
            }, { passive: true });

            lightbox.addEventListener('touchmove', (e) => {
                if (e.touches.length === 2) {
                    e.preventDefault();
                    const distance = getTouchDistance(e.touches[0], e.touches[1]);
                    const ratio = distance / initialDistance;
                    lightboxZoom = Math.min(Math.max(0.5, initialZoom * ratio), 5);
                    applyZoom();
                } else if (e.touches.length === 1 && lightboxDragging && lightboxZoom > 1) {
                    e.preventDefault();
                    lightboxPanX = e.touches[0].clientX - lightboxDragStartX;
                    lightboxPanY = e.touches[0].clientY - lightboxDragStartY;
                    applyZoom();
                }
                showLightboxControls();
                scheduleControlsFade();
            }, { passive: false });

            lightbox.addEventListener('touchend', (e) => {
                if (e.touches.length === 0) {
                    lightboxDragging = false;
                    
                    // Swipe navigation si non zoomé
                    if (lightboxZoom <= 1 && e.changedTouches.length === 1) {
                        // Géré ailleurs
                    }
                }
            }, { passive: true });

            // ============================================================
            // SWIPE NAVIGATION (si non zoomé)
            // ============================================================
            let swipeStartX = 0;
            let swipeStartY = 0;
            let swipeMoved = false;

            lightbox.addEventListener('touchstart', (e) => {
                if (e.touches.length === 1 && lightboxZoom <= 1) {
                    swipeStartX = e.touches[0].clientX;
                    swipeStartY = e.touches[0].clientY;
                    swipeMoved = false;
                }
            }, { passive: true });

            lightbox.addEventListener('touchmove', (e) => {
                if (e.touches.length === 1 && lightboxZoom <= 1) {
                    const dx = e.touches[0].clientX - swipeStartX;
                    const dy = e.touches[0].clientY - swipeStartY;
                    if (Math.abs(dx) > 10 || Math.abs(dy) > 10) {
                        swipeMoved = true;
                    }
                }
            }, { passive: true });

            lightbox.addEventListener('touchend', (e) => {
                if (e.touches.length === 0 && lightboxZoom <= 1 && e.changedTouches.length === 1) {
                    const dx = e.changedTouches[0].clientX - swipeStartX;
                    const dy = e.changedTouches[0].clientY - swipeStartY;

                    if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) {
                        if (dx > 0) lightboxPrev();
                        else lightboxNext();
                    }
                }
            }, { passive: true });

            // ============================================================
            // PAN À LA SOURIS (desktop, si zoomé)
            // ============================================================
            img.addEventListener('mousedown', (e) => {
                if (lightboxZoom > 1) {
                    e.preventDefault();
                    lightboxDragging = true;
                    lightboxDragStartX = e.clientX - lightboxPanX;
                    lightboxDragStartY = e.clientY - lightboxPanY;
                    img.classList.add('dragging');
                }
            });

            document.addEventListener('mousemove', (e) => {
                if (lightboxDragging && lightboxZoom > 1) {
                    lightboxPanX = e.clientX - lightboxDragStartX;
                    lightboxPanY = e.clientY - lightboxDragStartY;
                    applyZoom();
                }
            });

            document.addEventListener('mouseup', () => {
                lightboxDragging = false;
                img.classList.remove('dragging');
            });

            // ============================================================
            // FADE OUT DES CONTRÔLES
            // ============================================================
            lightbox.addEventListener('mousemove', () => {
                showLightboxControls();
                scheduleControlsFade();
            });

            lightbox.addEventListener('click', () => {
                showLightboxControls();
                scheduleControlsFade();
            });
        }

        function getTouchDistance(touch1, touch2) {
            const dx = touch1.clientX - touch2.clientX;
            const dy = touch1.clientY - touch2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        function showLightboxControls() {
            const controls = document.getElementById('lightbox-controls');
            if (controls) controls.classList.remove('hidden-fade');
        }

        function scheduleControlsFade() {
            clearTimeout(lightboxFadeTimeout);
            lightboxFadeTimeout = setTimeout(() => {
                const controls = document.getElementById('lightbox-controls');
                const lightbox = document.getElementById('lightbox');
                // Ne pas cacher si le lightbox est fermé
                if (controls && lightbox && !lightbox.classList.contains('hidden')) {
                    controls.classList.add('hidden-fade');
                }
            }, 3000);
        }

        // ============================================================
        // CLAVIER
        // ============================================================
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightbox');
            const isLightboxOpen = lightbox && !lightbox.classList.contains('hidden');
            if (!isLightboxOpen) return;

            if (e.key === 'Escape') {
                e.preventDefault();
                closeLightbox();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                lightboxNext();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                lightboxPrev();
            } else if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                lightboxZoom = Math.min(lightboxZoom + 0.25, 5);
                applyZoom();
            } else if (e.key === '-') {
                e.preventDefault();
                lightboxZoom = Math.max(lightboxZoom - 0.25, 0.5);
                if (lightboxZoom <= 1) {
                    lightboxPanX = 0;
                    lightboxPanY = 0;
                }
                applyZoom();
            } else if (e.key === '0') {
                e.preventDefault();
                resetZoom();
            }
        });
    </script>
    @endpush
@endif