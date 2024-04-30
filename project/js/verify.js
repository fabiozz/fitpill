
let passwordInp = document.querySelector('.password-input');
let passwordChecklist = document.querySelectorAll('.list-item');

let regex = [
    { regex: /.{8,}/},
    { regex: /[0-9]/},
    { regex: /[a-z]/},
    { regex: /[A-Z]/},
    { regex: /[^A-Za-z0-9]/}
]

passwordInp.addEventListener('keyup', () => {
    regex.forEach((item, i) => {
        let valid = item.regex.test(passwordInp.value);

        if(valid){
            passwordChecklist[i].classList.add('checked');
        } else{
            passwordChecklist[i].classList.remove('checked');
        }
    })
})