(function () {
    'use strict';

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    var toastEl = document.getElementById('toast');
    var toastTimer = null;
    function toast(message) {
        if (!toastEl) {
            return;
        }
        toastEl.textContent = message;
        toastEl.classList.add('is-show');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(function () {
            toastEl.classList.remove('is-show');
        }, 2600);
    }

    /* header state */
    var header = document.getElementById('siteHeader');
    function syncHeader() {
        if (header) {
            header.classList.toggle('is-scrolled', window.scrollY > 16);
        }
    }
    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });

    /* flash messages */
    document.querySelectorAll('.flash').forEach(function (flash) {
        var close = flash.querySelector('.flash-close');
        var hide = function () {
            flash.style.opacity = '0';
            flash.style.transform = 'translateX(16px)';
            window.setTimeout(function () { flash.remove(); }, 260);
        };
        if (close) {
            close.addEventListener('click', hide);
        }
        window.setTimeout(hide, 6000);
    });

    /* hero carousel */
    var hero = document.querySelector('[data-hero]');
    if (hero) {
        var slides = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-slide]'));
        var dots = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-dot]'));
        var current = 0;
        var autoTimer = null;
        var AUTO_MS = 7000;

        var show = function (index) {
            if (!slides.length) {
                return;
            }
            current = (index + slides.length) % slides.length;
            slides.forEach(function (slide, i) {
                slide.classList.toggle('is-active', i === current);
            });
            dots.forEach(function (dot, i) {
                dot.classList.toggle('is-active', i === current);
            });
        };
        var startAuto = function () {
            stopAuto();
            autoTimer = window.setInterval(function () { show(current + 1); }, AUTO_MS);
        };
        var stopAuto = function () {
            if (autoTimer) {
                window.clearInterval(autoTimer);
                autoTimer = null;
            }
        };

        var prev = hero.querySelector('[data-hero-prev]');
        var next = hero.querySelector('[data-hero-next]');
        if (prev) {
            prev.addEventListener('click', function () { show(current - 1); startAuto(); });
        }
        if (next) {
            next.addEventListener('click', function () { show(current + 1); startAuto(); });
        }
        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { show(i); startAuto(); });
        });
        hero.addEventListener('mouseenter', stopAuto);
        hero.addEventListener('mouseleave', startAuto);

        var touchStartX = null;
        hero.addEventListener('touchstart', function (event) {
            touchStartX = event.touches[0].clientX;
        }, { passive: true });
        hero.addEventListener('touchend', function (event) {
            if (touchStartX === null) {
                return;
            }
            var delta = event.changedTouches[0].clientX - touchStartX;
            if (Math.abs(delta) > 48) {
                show(current + (delta < 0 ? 1 : -1));
                startAuto();
            }
            touchStartX = null;
        }, { passive: true });

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            startAuto();
        }
    }

    /* copy share link */
    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text).then(function () { return true; }).catch(function () { return legacyCopy(text); });
        }
        return Promise.resolve(legacyCopy(text));
    }
    function legacyCopy(text) {
        var area = document.createElement('textarea');
        area.value = text;
        area.style.position = 'fixed';
        area.style.top = '-1000px';
        area.style.opacity = '0';
        document.body.appendChild(area);
        area.select();
        var ok = false;
        try {
            ok = document.execCommand('copy');
        } catch (error) {
            ok = false;
        }
        area.remove();
        return ok;
    }

    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-copy]');
        if (!button) {
            return;
        }
        var url = button.getAttribute('data-url') || '';
        var code = button.getAttribute('data-code') || '';
        var text = code ? url + '  提取码：' + code : url;
        copyText(text).then(function (ok) {
            if (ok) {
                toast(code ? '已复制链接与提取码 ' + code : '链接已复制');
            } else {
                toast('复制失败，请手动选择链接');
            }
        });
    });

    /* voting */
    document.querySelectorAll('[data-vote-id]').forEach(function (button) {
        button.addEventListener('click', function () {
            var payload = new FormData();
            payload.append('id', button.getAttribute('data-vote-id'));
            payload.append('_token', csrfToken);

            fetch('/vote', {
                method: 'POST',
                body: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function (response) {
                if (response.status === 401) {
                    toast('请先登录后再投票');
                    window.setTimeout(function () {
                        window.location.href = '/login?next=' + encodeURIComponent(window.location.pathname + window.location.search);
                    }, 700);
                    return null;
                }
                return response.json();
            }).then(function (data) {
                if (!data) {
                    return;
                }
                if (!data.ok) {
                    toast(data.message || '操作失败');
                    return;
                }
                button.classList.toggle('is-voted', data.voted);
                button.setAttribute('aria-pressed', String(data.voted));
                var counter = button.querySelector('[data-vote-count]');
                if (counter) {
                    counter.textContent = data.votes;
                }
                toast(data.voted ? '已顶，热度 +1' : '已取消');
            }).catch(function () {
                toast('网络异常，请稍后再试');
            });
        });
    });

    /* resource table: filter / search / sort */
    var table = document.querySelector('[data-resource-table]');
    if (table) {
        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
        var emptyTip = document.querySelector('[data-table-empty]');
        var searchInput = document.getElementById('resourceSearch');
        var sortSelect = document.getElementById('resourceSort');
        var chips = Array.prototype.slice.call(document.querySelectorAll('[data-quality-filter] .chip'));
        var activeQuality = 'all';

        var sizeInMb = function (text) {
            var match = String(text || '').replace(/\s+/g, '').match(/([\d.]+)(TB|GB|MB|T|G|M)/i);
            if (!match) {
                return 0;
            }
            var value = parseFloat(match[1]) || 0;
            var unit = match[2].toUpperCase();
            if (unit.indexOf('T') === 0) {
                return value * 1024 * 1024;
            }
            if (unit.indexOf('G') === 0) {
                return value * 1024;
            }
            return value;
        };

        var applyFilters = function () {
            var keyword = searchInput ? searchInput.value.trim().toLowerCase() : '';
            var visible = 0;
            rows.forEach(function (row) {
                var quality = (row.getAttribute('data-quality') || '').toUpperCase();
                var matchesQuality = activeQuality === 'all'
                    || (activeQuality === '4k' && (quality === '4K' || quality === '2160P' || quality === 'UHD'))
                    || quality === activeQuality.toUpperCase();
                var matchesKeyword = !keyword || (row.getAttribute('data-title') || '').indexOf(keyword) !== -1;
                var show = matchesQuality && matchesKeyword;
                row.classList.toggle('is-hidden', !show);
                if (show) {
                    visible += 1;
                }
            });
            if (emptyTip) {
                emptyTip.hidden = visible > 0;
            }
        };

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(function (item) { item.classList.remove('is-active'); });
                chip.classList.add('is-active');
                activeQuality = chip.getAttribute('data-filter') || 'all';
                applyFilters();
            });
        });
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                var mode = sortSelect.value;
                rows.sort(function (a, b) {
                    if (mode === 'time') {
                        return Number(b.getAttribute('data-time')) - Number(a.getAttribute('data-time'));
                    }
                    if (mode === 'size') {
                        return sizeInMb(b.getAttribute('data-size')) - sizeInMb(a.getAttribute('data-size'));
                    }
                    return Number(b.getAttribute('data-votes')) - Number(a.getAttribute('data-votes'));
                });
                var body = table.querySelector('tbody');
                rows.forEach(function (row) { body.appendChild(row); });
            });
        }
        applyFilters();
    }

    /* dialogs */
    document.querySelectorAll('[data-open-modal]').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            var dialog = document.getElementById(trigger.getAttribute('data-open-modal'));
            if (!dialog) {
                return;
            }
            if (typeof dialog.showModal === 'function') {
                dialog.showModal();
                var firstInput = dialog.querySelector('input[name="title"]');
                if (firstInput) {
                    window.setTimeout(function () { firstInput.focus(); }, 60);
                }
            } else {
                dialog.setAttribute('open', 'open');
            }
        });
    });
    document.querySelectorAll('[data-close-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            var dialog = button.closest('dialog');
            if (dialog) {
                dialog.close();
            }
        });
    });
    document.querySelectorAll('dialog.modal').forEach(function (dialog) {
        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    });

    /* destructive confirm */
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.getAttribute('data-confirm') || '确定继续？')) {
                event.preventDefault();
            }
        });
    });

    /* reveal on scroll */
    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        var targets = document.querySelectorAll('.row-section, .cta-card, .repo-grid');
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
        targets.forEach(function (target) {
            target.classList.add('reveal');
            observer.observe(target);
        });
    }
})();
