import './main.css'
import PhotoSwipeLightbox from 'photoswipe/lightbox'
import PhotoSwipe from 'photoswipe'

// Mobile menu toggle
const toggle = document.getElementById('menu-toggle')
const menu = document.getElementById('mobile-menu')
if (toggle && menu) {
  toggle.addEventListener('click', () => {
    const isOpen = !menu.classList.contains('pointer-events-none')
    menu.classList.toggle('opacity-0', isOpen)
    menu.classList.toggle('opacity-100', !isOpen)
    menu.classList.toggle('-translate-y-1', isOpen)
    menu.classList.toggle('translate-y-0', !isOpen)
    menu.classList.toggle('pointer-events-none', isOpen)
    menu.classList.toggle('pointer-events-auto', !isOpen)
  })
}

// Shared selection form reference
const form = document.getElementById('selection-form')

// Image selection counter
if (form) {
  const countEl = document.getElementById('selection-count')
  form.addEventListener('change', () => {
    const checked = form.querySelectorAll('input[name="images[]"]:checked').length
    if (countEl) countEl.textContent = checked
  })
}

// Hero slideshow
const slides = document.querySelectorAll('.slide')
if (slides.length) {
  const firstImg = document.getElementById('hero-first-img')
  const overlay = document.getElementById('hero-overlay')

  function onFirstImageReady() {
    if (overlay) {
      overlay.classList.remove('opacity-0')
      overlay.classList.add('opacity-100')
    }
    if (slides.length > 1) {
      let current = 0
      setInterval(() => {
        slides[current].classList.remove('opacity-100')
        slides[current].classList.add('opacity-0')
        current = (current + 1) % slides.length
        slides[current].classList.remove('opacity-0')
        slides[current].classList.add('opacity-100')
      }, 3000)
    }
  }

  if (!firstImg || firstImg.complete) {
    onFirstImageReady()
  } else {
    firstImg.addEventListener('load', onFirstImageReady)
  }
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

// Lightbox with PhotoSwipe
const galleryItems = document.querySelectorAll('[data-lightbox]')

if (galleryItems.length) {
  const dataSource = Array.from(galleryItems).map(el => ({
    src: el.dataset.pswpSrc,
    width: parseInt(el.dataset.pswpWidth),
    height: parseInt(el.dataset.pswpHeight),
    msrc: el.querySelector('img')?.src,
    filename: el.dataset.filename || null,
  }))

  const lightbox = new PhotoSwipeLightbox({
    dataSource,
    pswpModule: PhotoSwipe,
    bgOpacity: 1,
    padding: { top: 40, bottom: 40, left: 40, right: 40 },
    getThumbBoundsFn: (index) => {
      const el = galleryItems[index]?.querySelector('img')
      if (!el) return
      const rect = el.getBoundingClientRect()
      return { x: rect.left, y: rect.top + window.scrollY, w: rect.width }
    },
  })

  if (form) {
    function updateSelectBtn() {
      const btn = document.querySelector('.pswp__select-btn')
      if (!btn || !lightbox.pswp) return
      const filename = dataSource[lightbox.pswp.currIndex]?.filename
      const checkbox = form.querySelector(`input[value="${CSS.escape(filename)}"]`)
      const isSelected = checkbox?.checked ?? false
      btn.textContent = isSelected ? '✓ Selected' : 'Select'
      btn.classList.toggle('is-selected', isSelected)
    }

    lightbox.on('uiRegister', () => {
      lightbox.pswp.ui.registerElement({
        name: 'select-button',
        appendTo: 'root',
        isButton: true,
        html: 'Select',
        className: 'pswp__select-btn',
        onClick: () => {
          const filename = dataSource[lightbox.pswp.currIndex]?.filename
          const checkbox = form.querySelector(`input[value="${CSS.escape(filename)}"]`)
          if (checkbox) {
            checkbox.checked = !checkbox.checked
            checkbox.dispatchEvent(new Event('change', { bubbles: true }))
          }
          updateSelectBtn()
        },
      })
    })

    lightbox.on('slideActivate', updateSelectBtn)
    lightbox.on('openComplete', updateSelectBtn)
    form.addEventListener('change', updateSelectBtn)
  }

  lightbox.init()

  galleryItems.forEach((el, i) => {
    el.addEventListener('click', () => lightbox.loadAndOpen(i))
  })
}
