function setupPasswordToggle(inputFieldId, buttonId, viewIconId, hideIconId) {
    const input = document.getElementById(inputFieldId);
    const btn = document.getElementById(buttonId);
    const viewIcon = document.getElementById(viewIconId);
    const hideIcon = document.getElementById(hideIconId);

    if (!input || !btn || !viewIcon || !hideIcon) return;

    btn.addEventListener('click', () => {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        viewIcon.classList.toggle('hidden', isPassword);
        hideIcon.classList.toggle('hidden', !isPassword);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupPasswordToggle('password', 'togglePassword', 'eyeIcon', 'eyeOffIcon');
    setupPasswordToggle('repeatPassword', 'toggleRepeatPassword', 'repeatEyeIcon', 'repeatEyeOffIcon');
});
