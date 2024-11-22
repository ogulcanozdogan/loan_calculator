function syncSliderAndInput(source) {
    const input = document.getElementById('loanAmountInput');
    const slider = document.getElementById('loanAmountSlider');
    if (source === 'slider') {
        input.value = slider.value;
    } else if (source === 'input') {
        slider.value = input.value;
    }
}