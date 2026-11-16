const authShell = document.querySelector('#auth-shell');
const registerSwitch = document.querySelector('.register-switch');
const loginSwitch = document.querySelector('.login-switch');
if (authShell && registerSwitch && loginSwitch) {
    registerSwitch.addEventListener('click', () => authShell.classList.add('active'));
    loginSwitch.addEventListener('click', () => authShell.classList.remove('active'));
}
