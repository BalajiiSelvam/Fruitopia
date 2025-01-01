document.getElementById('register-form').addEventListener('submit', event=>{
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const data = { username,password };

    fetch('./php/login.php',{
        method : 'POST',
        headers : {
            'Content-Type':'application/json'
        },
        body: JSON.stringify(data),
    })

    .then(response=>response.json())
    .then(result=>{
        if(result.success){
            if (result.role === 'user') {
                window.location.href = 'user_homepage.html';
            } else if (result.role === 'admin') {
                window.location.href = 'admin_homepage.html';
            }
        }
        else{
            alert("Error : "+ result.message);
        }
    })

    .catch(error=>{
        console.error('Error : ',error);
        alert('An error occured during login.');
    })
})








