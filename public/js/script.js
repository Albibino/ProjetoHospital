window.addEventListener('DOMContentLoaded', (event) => {
    const slider = document.getElementById('slider');
    const sliderValue = document.getElementById('sliderValue');

    function updateSlider() {
        const value = parseInt(slider.value);
        const min = parseInt(slider.min);
        const max = parseInt(slider.max);
        const percentage = ((value - min) / (max - min)) * 100;

        sliderValue.textContent = value;

        if (value > 5) {
            slider.style.background = `linear-gradient(to right, #4CAF50 0%, #4CAF50 ${percentage}%, #ddd ${percentage}%, #ddd 100%)`;
        } else if (value < 5) {
            slider.style.background = `linear-gradient(to right, #ddd 0%, #ddd ${percentage}%, #ff0000 ${percentage}%, #ff0000 100%)`;
        } else {
            slider.style.background = '#ddd';
        }
    }

    slider.addEventListener('input', updateSlider);

    updateSlider();

    function limparSlider() {
        slider.value = 5;
        updateSlider();
    }

    btnLimpar.addEventListener('click', limparSlider);

    function enviarValor() {
        const valor = slider.value;
        alert('Nota enviada: ' + valor);
        limparSlider();
    }

    btnEnviar.addEventListener('click', enviarValor);
});
