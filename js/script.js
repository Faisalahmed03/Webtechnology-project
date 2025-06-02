
document.addEventListener('DOMContentLoaded', function() {
    console.log("Online Quiz System Loaded");

    
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let allValid = true;
            form.querySelectorAll('input[required]').forEach(input => {
                if (!input.value) {
                    allValid = false;
                    
                    console.error("Input field is required:", input.name);
                }
            });
            if (!allValid) {
                event.preventDefault(); 
                alert("Please fill in all required fields.");
            }
        });
    });
});
