document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.contact-form');
  if (!form) return;

  const responseContainer = document.getElementById('form-response');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
      const res = await fetch(form.action || window.location.href, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: new FormData(form),
      });

      // Try to parse JSON; if HTML came back (e.g., an error page), show generic error
      let data;
      try { data = await res.json(); }
      catch { throw new Error('Unexpected response from server'); }

      responseContainer.innerHTML = `<p class="${data.success ? 'success' : 'error'}">${data.message}</p>`;

      if (res.ok && data.success) form.reset();
    } catch (err) {
      console.error(err);
      responseContainer.innerHTML = `<p class="error">An error occurred. Please try again.</p>`;
    }
  });
});
