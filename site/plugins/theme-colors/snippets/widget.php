<?php
// Floating colour picker, rendered only for logged-in Panel admins.
// Styled with plain CSS in fixed neutral colours so it stays readable
// whatever colours are being previewed.
if (kirby()->user()?->isAdmin() !== true) return;
?>
<div id="tc-widget">
  <button type="button" id="tc-toggle" aria-expanded="false" aria-controls="tc-panel" title="Site colours">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
      <circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18z" fill="currentColor"/>
    </svg>
  </button>
  <form id="tc-panel" hidden>
    <p class="tc-title">Site colours</p>
    <label>Background <input type="color" name="bgColor"></label>
    <label>Text <input type="color" name="textColor"></label>
    <input type="hidden" name="csrf" value="<?= csrf() ?>">
    <div class="tc-actions">
      <button type="button" id="tc-reset">Reset</button>
      <button type="submit">Save</button>
    </div>
    <p id="tc-status" role="status"></p>
  </form>
</div>

<style>
  #tc-widget { position: fixed; right: 16px; bottom: 16px; z-index: 9999; font: 13px/1.4 system-ui, sans-serif; color: #222; }
  #tc-toggle { width: 44px; height: 44px; border-radius: 50%; border: 1px solid #ccc; background: #fff; color: #222; display: grid; place-items: center; cursor: pointer; box-shadow: 0 2px 8px rgb(0 0 0 / .15); }
  #tc-panel { position: absolute; right: 0; bottom: 56px; width: 220px; padding: 14px; background: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 16px rgb(0 0 0 / .15); }
  #tc-panel[hidden] { display: none; }
  #tc-panel .tc-title { margin: 0 0 10px; font-weight: 600; }
  #tc-panel label { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
  #tc-panel input[type=color] { width: 44px; height: 28px; padding: 0; border: 1px solid #ccc; border-radius: 4px; background: none; cursor: pointer; }
  #tc-panel .tc-actions { display: flex; gap: 8px; margin-top: 12px; }
  #tc-panel .tc-actions button { flex: 1; padding: 6px 0; border: 1px solid #222; border-radius: 4px; background: #fff; color: #222; cursor: pointer; }
  #tc-panel .tc-actions button[type=submit] { background: #222; color: #fff; }
  #tc-status { margin: 8px 0 0; min-height: 1.4em; font-size: 12px; color: #555; }
</style>

<script>
(() => {
  const root = document.documentElement
  const saved = document.getElementById('theme-colors')
  const toggle = document.getElementById('tc-toggle')
  const form = document.getElementById('tc-panel')
  const status = document.getElementById('tc-status')
  const vars = { bgColor: '--color-cream', textColor: '--color-darkbrown' }

  // <input type=color> only accepts #rrggbb — expand minified #rgb values
  const hex = (value) => value.length === 4 ? '#' + [...value.slice(1)].map((c) => c + c).join('') : value
  const read = (cssVar) => hex(getComputedStyle(root).getPropertyValue(cssVar).trim())

  // Colours from main.css alone, ignoring the saved override and any preview
  const defaults = () => {
    saved.disabled = true
    const values = {}
    for (const [key, cssVar] of Object.entries(vars)) {
      root.style.removeProperty(cssVar)
      values[key] = read(cssVar)
    }
    saved.disabled = false
    return values
  }

  for (const [key, cssVar] of Object.entries(vars)) {
    form.elements[key].value = read(cssVar)
    form.elements[key].addEventListener('input', (e) => root.style.setProperty(cssVar, e.target.value))
  }

  toggle.addEventListener('click', () => {
    form.hidden = !form.hidden
    toggle.setAttribute('aria-expanded', String(!form.hidden))
  })

  // Preview the main.css colours; Save then clears the override
  document.getElementById('tc-reset').addEventListener('click', () => {
    const values = defaults()
    for (const [key, cssVar] of Object.entries(vars)) {
      form.elements[key].value = values[key]
      root.style.setProperty(cssVar, values[key])
      form.elements[key].dataset.reset = '1'
    }
    status.textContent = 'Defaults previewed — Save to keep.'
  })
  for (const key of Object.keys(vars)) {
    form.elements[key].addEventListener('input', (e) => delete e.target.dataset.reset)
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault()
    const body = new FormData(form)
    for (const key of Object.keys(vars)) {
      if (form.elements[key].dataset.reset) body.set(key, '')
    }
    status.textContent = 'Saving…'
    try {
      const res = await fetch('<?= url('theme-colors') ?>', { method: 'POST', body })
      const json = await res.json()
      status.textContent = res.ok ? 'Saved.' : (json.error || 'Error saving.')
    } catch {
      status.textContent = 'Error saving.'
    }
  })
})()
</script>
