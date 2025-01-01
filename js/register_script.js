document.getElementById('register-form').addEventListener('submit', event => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    if(password.length < 8){
        alert('Password length should not be less than 8 character.');
        return false;
    }
    else if(!/\d/.test(password)){
        alert('Password should contain atleast one digit.');
        return false;
    }
    else if(!/[A-Z]/.test(password)){
        alert('Password should contain alteast one alphabet.');
        return false;
    }
    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)){
        alert('Password should contain atleast one special character.');
        return false;
    }
    else{
        const data = { username, password, role };

        fetch('./php/register.php',{
            method : 'POST',
            headers : {
                'Content-Type' : 'application/json'
            },
            body : JSON.stringify(data),
        })

        .then(response=>response.json())
        .then(result=> {
            if(result.success){
                alert('Resgistration Successful.');
                console.log(result);
                //document.getElementById('register-form').reset();
                window.location.href = 'login.html';
            }
            else{
                alert('Error : '+result.message);
            }
        })

        .catch(error=>{
            console.error('Error : ',error);
            alert('An error occured during registration');
        })
    }
})