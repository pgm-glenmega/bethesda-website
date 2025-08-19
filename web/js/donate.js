(function() {
      const form = document.getElementById('donation-form');
      if (!form) return;

      const radios = form.querySelectorAll('input[name="amountChoice"]');
      const customWrap = form.querySelector('.custom-amount');
      const customInput = form.querySelector('input[name="customAmount"]');
      const finalAmount = form.querySelector('input[name="amount"]');

      function updateAmount() {
        const chosen = form.querySelector('input[name="amountChoice"]:checked');
        if (!chosen) return;

        if (chosen.value === 'custom') {
          customWrap.classList.add('show');
          const val = parseFloat(customInput.value || '0');
          finalAmount.value = isFinite(val) && val > 0 ? Math.round(val) : '';
        } else {
          customWrap.classList.remove('show');
          finalAmount.value = parseInt(chosen.value, 10);
        }
      }

      radios.forEach(r => r.addEventListener('change', updateAmount));
      customInput.addEventListener('input', updateAmount);

      if (radios.length) { radios[0].checked = true; }
      updateAmount();

      form.addEventListener('submit', function(e) {
        if (!finalAmount.value) {
          e.preventDefault();
          customWrap.classList.add('show');
          customInput.focus();
        }
      });
    })();