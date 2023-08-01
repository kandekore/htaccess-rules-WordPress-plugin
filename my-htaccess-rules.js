document.addEventListener('DOMContentLoaded', function() {
    const mainSwitch = document.getElementById('main-switch');
    const dependents = document.querySelectorAll('.dependent-checkbox');

    mainSwitch.addEventListener('change', function() {
        dependents.forEach(function(el) {
            el.disabled = !mainSwitch.checked;
        });
    });
});
