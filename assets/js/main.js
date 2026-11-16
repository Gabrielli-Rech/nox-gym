const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.main-nav');
if (menuButton && navigation) {
    menuButton.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('open');
        menuButton.setAttribute('aria-expanded', String(isOpen));
        menuButton.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
    });
}

const bmiForm = document.querySelector('#bmi-form');
if (bmiForm) {
    bmiForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const weight = Number(document.querySelector('#weight').value.replace(',', '.'));
        const height = Number(document.querySelector('#height').value.replace(',', '.'));
        const result = document.querySelector('#bmi-result');
        if (!weight || !height || weight <= 0 || height <= 0) {
            result.style.display = 'block';
            result.textContent = 'Informe peso e altura válidos.';
            return;
        }
        const meters = height > 3 ? height / 100 : height;
        const bmi = weight / (meters * meters);
        let classification = 'Obesidade';
        if (bmi < 18.5) classification = 'Abaixo do peso';
        else if (bmi < 25) classification = 'Peso adequado';
        else if (bmi < 30) classification = 'Sobrepeso';
        result.style.display = 'block';
        result.innerHTML = `<strong>Seu IMC: ${bmi.toFixed(1).replace('.', ',')}</strong><br>Classificação: ${classification}.<br><small>Resultado informativo; não substitui avaliação profissional.</small>`;
    });
}
