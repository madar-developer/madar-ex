<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.8.2/build/js/intlTelInputWithUtils.min.js"></script>
<script>
    (function () {
        var input = document.querySelector('#driver-phone');
        if (!input || typeof window.intlTelInput !== 'function') {
            return;
        }

        var iti = window.intlTelInput(input, {
            initialCountry: 'sa',
            preferredCountries: ['sa', 'eg', 'ae', 'kw', 'bh', 'qa', 'om', 'ye', 'jo'],
            separateDialCode: true,
            formatOnDisplay: true,
            autoPlaceholder: 'aggressive',
            strictMode: true
        });

        var form = input.closest('form');
        if (!form) {
            return;
        }

        form.addEventListener('submit', function (e) {
            if (!input.value.trim()) {
                return;
            }

            if (typeof iti.isValidNumber === 'function' && !iti.isValidNumber()) {
                e.preventDefault();
                alert('يرجى إدخال رقم تليفون صحيح بالصيغة الدولية');
                input.focus();
                return false;
            }

            input.value = iti.getNumber();
        });
    })();
</script>
