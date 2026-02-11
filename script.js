// Track if balance is currently hidden
let isBalanceHidden = false; 

function toggleBalance() {
    const mainDisplay = document.getElementById('mainBalance');
    const eyeIcon = document.getElementById('eyeIcon');
    
    // Retrieve the actual numerical value stored in the HTML attribute
    const realValue = mainDisplay.getAttribute('data-value');
    
    if (!isBalanceHidden) {
        // HIDE action
        mainDisplay.innerText = "₱ ••••••";
        eyeIcon.innerText = "🙈"; // Change icon to 'see no evil' monkey or closed eye
        isBalanceHidden = true;
    } else {
        // SHOW action
        mainDisplay.innerText = realValue;
        eyeIcon.innerText = "👁️"; // Change back to open eye
        isBalanceHidden = false;
    }
}

//LOGOUT
function openLogoutModal() { document.getElementById('logoutModal').style.display = 'flex'; }
function closeLogoutModal() { document.getElementById('logoutModal').style.display = 'none'; }

function userlogin() {
        window.location.href = "login.php";
    }

//UPDATE BTN
document.querySelector(".update").addEventListener("click", () => {
        alert("Account balance updated!");
});


