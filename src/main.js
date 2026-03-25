import './main.css'
import Swiper from 'swiper'
import { Zoom, Navigation, Keyboard } from 'swiper/modules'

// Mobile menu toggle
const toggle = document.getElementById('menu-toggle')
const menu = document.getElementById('mobile-menu')
if (toggle && menu) {
  toggle.addEventListener('click', () => menu.classList.toggle('hidden'))
}

// Image selection counter
const form = document.getElementById('selection-form')
if (form) {
  const countEl = document.getElementById('selection-count')
  form.addEventListener('change', () => {
    const checked = form.querySelectorAll('input[name="images[]"]:checked').length
    if (countEl) countEl.textContent = checked
  })
}

// Scroll fade-in
const fadeEls = document.querySelectorAll('.fade-in')
if (fadeEls.length) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 80)
        observer.unobserve(entry.target)
      }
    })
  }, { threshold: 0.1 })
  fadeEls.forEach(el => observer.observe(el))
}

// Lightbox with Swiper
const lightbox = document.getElementById('lightbox')
const lightboxClose = document.getElementById('lightbox-close')
const galleryItems = document.querySelectorAll('[data-lightbox]')

if (lightbox && galleryItems.length) {
  let swiper = null
  let isClosing = false

  function openLightbox(index) {
    isClosing = false
    lightbox.classList.add('open')
    document.body.style.overflow = 'hidden'

    if (swiper) swiper.destroy(true, true)

    swiper = new Swiper('#lightbox-swiper', {
      modules: [Zoom, Navigation, Keyboard],
      initialSlide: index,
      zoom: { toggle: false },
      navigation: {
        nextEl: '#lightbox-next',
        prevEl: '#lightbox-prev',
      },
      keyboard: { enabled: true },
    })

    // Single-click zoom + click-on-background-to-close
    swiper.slides.forEach(slide => {
      const zoomContainer = slide.querySelector('.swiper-zoom-container')
      if (!zoomContainer) return
      zoomContainer.addEventListener('click', (e) => {
        if (isClosing) return
        if (e.target.tagName === 'IMG') {
          if (swiper.zoom.scale > 1) {
            swiper.zoom.out()
            zoomContainer.classList.remove('is-zoomed')
          } else {
            swiper.zoom.in()
            zoomContainer.classList.add('is-zoomed')
          }
        } else {
          closeLightbox()
        }
      })
    })

    // Reset zoom class on slide change
    swiper.on('slideChange', () => {
      document.querySelectorAll('#lightbox-swiper .swiper-zoom-container.is-zoomed')
        .forEach(el => el.classList.remove('is-zoomed'))
    })
  }

  function closeLightbox() {
    if (isClosing) return
    isClosing = true
    lightbox.classList.add('closing')
    setTimeout(() => {
      lightbox.classList.remove('open', 'closing')
      document.body.style.overflow = ''
      if (swiper) { swiper.destroy(true, true); swiper = null }
      isClosing = false
    }, 300)
  }

  galleryItems.forEach((el, i) => {
    el.addEventListener('click', () => openLightbox(i))
  })

  lightboxClose.addEventListener('click', closeLightbox)
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox()
  })
}
