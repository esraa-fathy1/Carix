const visibilityToggle = document.querySelector('.visibility');
const input = document.querySelector('.passWord input');
var icon = document.querySelector('.visibility');

var password = true;

visibilityToggle.addEventListener('click', function () {
    if (password) {
        input.setAttribute('type', 'text');
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
    else {
        input.setAttribute('type', 'password');
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }

    password = !password;
})