import './main.css'

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
