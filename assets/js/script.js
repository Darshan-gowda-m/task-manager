document.addEventListener('DOMContentLoaded', function () {
  // Theme Toggle
  const themeToggle = document.getElementById('theme-toggle')
  let currentTheme = localStorage.getItem('theme') || 'light'
  document.documentElement.setAttribute('data-theme', currentTheme)

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      currentTheme = document.documentElement.getAttribute('data-theme')
      const newTheme = currentTheme === 'light' ? 'dark' : 'light'
      document.documentElement.setAttribute('data-theme', newTheme)
      localStorage.setItem('theme', newTheme)
    })
  }

  // Password Visibility Toggle
  const passwordToggles = document.querySelectorAll('.password-toggle')
  passwordToggles.forEach((toggle) => {
    toggle.addEventListener('click', (e) => {
      const input = e.target
        .closest('.form-group')
        ?.querySelector('input[type="password"], input[type="text"]')
      if (input) {
        const type =
          input.getAttribute('type') === 'password' ? 'text' : 'password'
        input.setAttribute('type', type)
        e.target.textContent = type === 'password' ? '👁️' : '👁️‍🗨️'
      }
    })
  })

  // Toast Notifications
  function showToast(message, type = 'info') {
    const toast = document.createElement('div')
    toast.className = `toast toast-${type}`
    toast.textContent = message
    document.body.appendChild(toast)

    setTimeout(() => {
      toast.remove()
    }, 3000)
  }

  // Show PHP Messages
  const urlParams = new URLSearchParams(window.location.search)
  const successMsg = urlParams.get('success')
  const errorMsg = urlParams.get('error')

  if (successMsg) showToast(successMsg, 'success')
  if (errorMsg) showToast(errorMsg, 'error')

  // Session Timeout Warning
  let timeoutWarning
  const sessionTimeout = 30 * 60 * 1000 // 30 minutes

  function resetSessionTimer() {
    clearTimeout(timeoutWarning)
    timeoutWarning = setTimeout(() => {
      showToast(
        'Your session will expire soon. Please save your work.',
        'warning'
      )
    }, sessionTimeout - 5 * 60 * 1000) // Warn 5 mins before
  }

  ;['click', 'mousemove', 'keypress'].forEach((event) => {
    document.addEventListener(event, resetSessionTimer)
  })

  resetSessionTimer()

  // Task Filtering
  const taskFilter = document.getElementById('task-filter')
  if (taskFilter) {
    taskFilter.addEventListener('change', function () {
      const status = this.value
      const tasks = document.querySelectorAll('.task-card')

      tasks.forEach((task) => {
        if (status === 'all' || task.dataset.status === status) {
          task.style.display = 'block'
        } else {
          task.style.display = 'none'
        }
      })
    })
  }

  // Task Search
  const taskSearch = document.getElementById('task-search')
  if (taskSearch) {
    taskSearch.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase()
      const tasks = document.querySelectorAll('.task-card')

      tasks.forEach((task) => {
        const title =
          task.querySelector('.task-title')?.textContent.toLowerCase() || ''
        const description =
          task.querySelector('.task-description')?.textContent.toLowerCase() ||
          ''

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          task.style.display = 'block'
        } else {
          task.style.display = 'none'
        }
      })
    })
  }

  // Loading Indicators on Form Submission
  const forms = document.querySelectorAll('form')
  forms.forEach((form) => {
    form.addEventListener('submit', function () {
      const submitBtn = this.querySelector('button[type="submit"]')
      if (submitBtn) {
        submitBtn.innerHTML = '<span class="loading"></span> Processing...'
        submitBtn.disabled = true
      }
    })
  })

  // Priority Badge Coloring
  const priorityBadges = document.querySelectorAll('.priority-badge')
  priorityBadges.forEach((badge) => {
    const priority = badge.textContent.trim().toLowerCase()
    if (['high', 'medium', 'low'].includes(priority)) {
      badge.classList.add(`priority-${priority}`)
    }
  })
})
