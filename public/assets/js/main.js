// Villa Plaisance V7 — Épure & Mouvement
'use strict';

const VP = {
    reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    isMobile: window.matchMedia('(pointer: coarse)').matches,

    init() {
        this.mobileNav();
        this.headerScroll();
        this.carousels();
        if (!this.reducedMotion) {
            this.scrollReveal();
            this.counterAnimation();
            this.parallaxHero();
            this.imageReveal();
            if (!this.isMobile) {
                this.customCursor();
                this.magneticButtons();
            }
        }
        this.faqAccordion();
        this.heroSlideshow();
    },

    // ─── Mobile nav toggle ───
    mobileNav() {
        const toggle = document.querySelector('.nav-toggle');
        const navList = document.querySelector('.nav-list');
        const closeBtn = document.querySelector('.nav-close');
        if (!toggle || !navList) return;

        const openNav = () => {
            toggle.setAttribute('aria-expanded', 'true');
            navList.classList.add('open');
        };
        const closeNav = () => {
            toggle.setAttribute('aria-expanded', 'false');
            navList.classList.remove('open');
        };

        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            expanded ? closeNav() : openNav();
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeNav);
        }

        // Close on link click
        navList.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', closeNav);
        });
    },

    // ─── Header — transparent → solid on scroll ───
    headerScroll() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        const hero = document.querySelector('.hero');
        const threshold = hero ? 100 : 10;

        const update = () => {
            header.classList.toggle('scrolled', window.scrollY > threshold);
        };
        update();
        window.addEventListener('scroll', update, { passive: true });
    },

    // ─── Scroll reveal ───
    scrollReveal() {
        // Auto-tag sections and grids
        document.querySelectorAll('.section').forEach(s => {
            if (!s.classList.contains('hero')) {
                s.classList.add('reveal');
            }
        });
        document.querySelectorAll('.stats-grid, .rooms-grid, .offers-grid, .reviews-grid, .articles-grid, .territoire-grid, .gallery-grid').forEach(g => {
            g.classList.add('reveal-stagger');
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));
    },

    // ─── Counter animation ───
    counterAnimation() {
        const counters = document.querySelectorAll('[data-count]');
        if (!counters.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        counters.forEach(el => observer.observe(el));
    },

    animateCounter(el) {
        const target = el.getAttribute('data-count');
        const numMatch = target.match(/([\d.]+)/);
        if (!numMatch) return;

        const num = parseFloat(numMatch[1]);
        const isFloat = numMatch[1].includes('.');
        const prefix = target.substring(0, target.indexOf(numMatch[1]));
        const suffix = target.substring(target.indexOf(numMatch[1]) + numMatch[1].length);
        const duration = 1200;
        const start = performance.now();

        const step = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out expo
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = isFloat ? (num * eased).toFixed(1) : Math.round(num * eased);
            el.textContent = prefix + current + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },

    // ─── Parallax hero ───
    parallaxHero() {
        const heroImage = document.querySelector('.hero .hero-image');
        if (!heroImage) return;

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const scrollY = window.scrollY;
                    const heroH = heroImage.parentElement.offsetHeight;
                    if (scrollY < heroH) {
                        // Subtle parallax: image moves slower than scroll
                        heroImage.style.transform = `translateY(${scrollY * 0.35}px)`;
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    },

    // ─── Image reveal ───
    imageReveal() {
        document.querySelectorAll('.prose-image, .offer-image, .room-image, .gallery-item').forEach(el => {
            el.classList.add('img-reveal');
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.img-reveal').forEach(el => observer.observe(el));
    },

    // ─── Custom cursor ───
    customCursor() {
        const cursor = document.createElement('div');
        cursor.classList.add('custom-cursor');
        document.body.appendChild(cursor);

        let cx = -100, cy = -100;
        let tx = -100, ty = -100;

        document.addEventListener('mousemove', (e) => {
            tx = e.clientX;
            ty = e.clientY;
            cursor.classList.add('visible');
        });

        document.addEventListener('mouseleave', () => {
            cursor.classList.remove('visible');
        });

        // Hover targets
        const hoverTargets = 'a, button, .btn-primary, .btn-secondary, .faq-question, .gallery-item';
        document.addEventListener('mouseover', (e) => {
            if (e.target.closest(hoverTargets)) cursor.classList.add('hover');
        });
        document.addEventListener('mouseout', (e) => {
            if (e.target.closest(hoverTargets)) cursor.classList.remove('hover');
        });

        // Smooth follow
        const follow = () => {
            cx += (tx - cx) * 0.15;
            cy += (ty - cy) * 0.15;
            cursor.style.left = cx + 'px';
            cursor.style.top = cy + 'px';
            requestAnimationFrame(follow);
        };
        follow();
    },

    // ─── Magnetic buttons ───
    magneticButtons() {
        document.querySelectorAll('.btn-primary, .btn-secondary').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
            });

            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
                btn.style.transition = 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                setTimeout(() => { btn.style.transition = ''; }, 400);
            });
        });
    },

    // ─── Carousels ───
    carousels() {
        document.querySelectorAll('.carousel').forEach(carousel => {
            const id = carousel.id;
            const track = carousel.querySelector('.carousel-track');
            const slides = carousel.querySelectorAll('.carousel-slide');
            if (!track || slides.length < 2) return;

            const prevBtn = document.querySelector(`.carousel-prev[data-carousel="${id}"]`) || carousel.querySelector('.carousel-prev');
            const nextBtn = document.querySelector(`.carousel-next[data-carousel="${id}"]`) || carousel.querySelector('.carousel-next');
            const counterEl = document.querySelector(`.carousel-counter[data-carousel="${id}"]`) || carousel.querySelector('.carousel-counter');
            const currentEl = counterEl?.querySelector('.carousel-current');

            let index = 0;
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;

            const getSlideWidth = () => {
                const slide = slides[0];
                const gap = parseFloat(getComputedStyle(track).gap) || 0;
                return slide.offsetWidth + gap;
            };

            const getMaxIndex = () => {
                const visibleWidth = carousel.offsetWidth;
                const totalWidth = track.scrollWidth;
                const slideWidth = getSlideWidth();
                return Math.max(0, Math.ceil((totalWidth - visibleWidth) / slideWidth));
            };

            const goTo = (i) => {
                index = Math.max(0, Math.min(i, getMaxIndex()));
                const offset = -index * getSlideWidth();
                track.style.transform = `translateX(${offset}px)`;
                prevTranslate = offset;
                currentTranslate = offset;
                updateUI();
            };

            const updateUI = () => {
                if (prevBtn) prevBtn.disabled = index === 0;
                if (nextBtn) nextBtn.disabled = index >= getMaxIndex();
                if (currentEl) currentEl.textContent = String(index + 1).padStart(2, '0');
            };

            if (prevBtn) prevBtn.addEventListener('click', () => goTo(index - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goTo(index + 1));

            // Touch / mouse drag
            const onStart = (e) => {
                isDragging = true;
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                track.style.transition = 'none';
            };

            const onMove = (e) => {
                if (!isDragging) return;
                const x = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                const diff = x - startX;
                currentTranslate = prevTranslate + diff;
                track.style.transform = `translateX(${currentTranslate}px)`;
            };

            const onEnd = () => {
                if (!isDragging) return;
                isDragging = false;
                track.style.transition = '';
                const diff = currentTranslate - prevTranslate;
                const threshold = getSlideWidth() * 0.2;
                if (diff < -threshold) goTo(index + 1);
                else if (diff > threshold) goTo(index - 1);
                else goTo(index);
            };

            carousel.addEventListener('mousedown', onStart);
            carousel.addEventListener('mousemove', onMove);
            carousel.addEventListener('mouseup', onEnd);
            carousel.addEventListener('mouseleave', () => { if (isDragging) onEnd(); });
            carousel.addEventListener('touchstart', onStart, { passive: true });
            carousel.addEventListener('touchmove', onMove, { passive: true });
            carousel.addEventListener('touchend', onEnd);

            // Keyboard
            carousel.setAttribute('tabindex', '0');
            carousel.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') goTo(index - 1);
                if (e.key === 'ArrowRight') goTo(index + 1);
            });

            updateUI();
        });
    },

    // ─── FAQ smooth open/close ───
    heroSlideshow() {
        const slideshow = document.querySelector('.hero-slideshow');
        if (!slideshow) return;
        const slides = slideshow.querySelectorAll('.hero-slide');
        if (slides.length < 2) return;
        let current = 0;
        const interval = this.reducedMotion ? 6000 : 4500;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, interval);
    },

    faqAccordion() {
        document.querySelectorAll('.faq-item').forEach(item => {
            const summary = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            if (!summary || !answer) return;

            // Wrap answer content for animation
            answer.style.overflow = 'hidden';
            answer.style.transition = 'max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s';

            item.addEventListener('toggle', () => {
                if (item.open) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                    answer.style.opacity = '1';
                } else {
                    answer.style.maxHeight = '0';
                    answer.style.opacity = '0';
                }
            });

            // Init closed state
            if (!item.open) {
                answer.style.maxHeight = '0';
                answer.style.opacity = '0';
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => VP.init());
