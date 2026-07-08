import './main.css'
import Splide from '@splidejs/splide'
import PhotoSwipeLightbox from 'photoswipe/lightbox'
import PhotoSwipe from 'photoswipe'

// Measure 65ch in the paragraph font and expose as --prose-measure for consistent text width
const measureProseWidth = () => {
  const ruler = document.createElement('div')
  ruler.style.cssText = 'position:absolute;visibility:hidden;width:65ch;font-family:var(--font-sans);font-size:1rem;pointer-events:none'
  document.body.appendChild(ruler)
  document.documentElement.style.setProperty('--prose-measure', ruler.offsetWidth + 'px')
  ruler.remove()
}
document.fonts.ready.then(() => requestAnimationFrame(measureProseWidth))

// Scroll-aware header
const siteHeader = document.getElementById('site-header')
const mobileMenu = document.getElementById('mobile-menu')

if (siteHeader) {
  const menuOpen = () => mobileMenu && !mobileMenu.classList.contains('pointer-events-none')
  const onScroll = () => {
    if (!menuOpen()) siteHeader.classList.toggle('is-scrolled', window.scrollY > 80)
  }
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
}

// Mobile menu toggle — forces cream header when opened in transparent hero state
const toggle = document.getElementById('menu-toggle')
if (toggle && mobileMenu) {
  toggle.addEventListener('click', () => {
    const isOpen = !mobileMenu.classList.contains('pointer-events-none')

    if (siteHeader?.classList.contains('header--hero')) {
      if (!isOpen && !siteHeader.classList.contains('is-scrolled')) {
        siteHeader.classList.add('is-scrolled')
      } else if (isOpen && window.scrollY <= 80) {
        siteHeader.classList.remove('is-scrolled')
      }
    }

    mobileMenu.classList.toggle('opacity-0', isOpen)
    mobileMenu.classList.toggle('opacity-100', !isOpen)
    mobileMenu.classList.toggle('-translate-y-1', isOpen)
    mobileMenu.classList.toggle('translate-y-0', !isOpen)
    mobileMenu.classList.toggle('pointer-events-none', isOpen)
    mobileMenu.classList.toggle('pointer-events-auto', !isOpen)
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
const heroEl = document.getElementById('hero-splide')
if (heroEl) {
  new Splide('#hero-splide', {
    type      : 'fade',
    rewind    : true,
    autoplay  : true,
    interval  : 4000,
    pauseOnHover: false,
    arrows    : false,
    pagination: false,
    drag      : false,
  }).mount()
}

// Services carousel
const servicesEl = document.getElementById('services-splide')
if (servicesEl) {
  new Splide('#services-splide', {
    type      : 'loop',
    perPage   : 3,
    gap       : '3rem',
    pagination: false,
    breakpoints: {
      768: { perPage: 2, gap: '1.5rem' },
      640: { perPage: 1, gap: '1rem' },
    },
  }).mount()
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

// Block galleries — one independent PhotoSwipe instance per gallery block
const blockGalleryGroups = {}
document.querySelectorAll('[data-gallery]').forEach(el => {
  const id = el.dataset.gallery
  if (!blockGalleryGroups[id]) blockGalleryGroups[id] = []
  blockGalleryGroups[id].push(el)
})

Object.values(blockGalleryGroups).forEach(items => {
  const dataSource = items.map(el => ({
    src: el.dataset.pswpSrc,
    width: parseInt(el.dataset.pswpWidth),
    height: parseInt(el.dataset.pswpHeight),
    msrc: el.querySelector('img')?.src,
  }))

  const lb = new PhotoSwipeLightbox({
    dataSource,
    pswpModule: PhotoSwipe,
    bgOpacity: 1,
    padding: { top: 40, bottom: 40, left: 40, right: 40 },
    getThumbBoundsFn: (index) => {
      const el = items[index]?.querySelector('img')
      if (!el) return
      const rect = el.getBoundingClientRect()
      return { x: rect.left, y: rect.top + window.scrollY, w: rect.width }
    },
  })

  lb.init()

  items.forEach((el, i) => {
    el.addEventListener('click', () => lb.loadAndOpen(i))
  })
})
