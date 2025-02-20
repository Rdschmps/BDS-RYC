/**
 * Generic form submission handler
 * @param {string} formId - ID of the form to handle
 * @param {Object} actionMap - Mapping of form fields to API action and parameters
 * @param {function} [preprocessData] - Optional function to preprocess form data
 */
function setupFormSubmission(formId, actionMap, preprocessData) {
    const form = document.getElementById(formId);
    const errorElement = document.getElementById(`${formId}Error`);

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        // Collecting form data
        const data = {};
        actionMap.fields.forEach(field => {
            const input = document.getElementById(field.id);
            data[field.key] = input.value.trim();
        });

        // Optional preprocessing of form data
        if (preprocessData) {
            preprocessData(data);
        }

        // Construct the request payload
        const requestData = {
            service: actionMap.service,
            action: actionMap.action,
            data: data
        };

        if (errorElement) {
            errorElement.textContent = "";
        }

        try {
            const result = await postData(apiUrl, requestData);
            if (result.error) {
                // Display error message
                if (errorElement) {
                    errorElement.textContent = `Error: ${result.error}`;
                }
            } else {
                // Success handling
                if (result.success) {
                    if (result.redirect) {
                        // Redirect if applicable
                        window.location.href = result.redirect;
                    } else {
                        alert(`${actionMap.successMessage || 'Operation'} successful!`);
                    }
                    console.log(result);
                    form.reset();
                }
            }
        } catch (error) {
            console.error("Submission error:", error);
            if (errorElement) {
                errorElement.textContent = `Error: ${error.message}`;
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    Object.keys(formConfigurations).forEach(formId => {
        setupFormSubmission(
            formId, 
            formConfigurations[formId], 
            formConfigurations[formId].preprocessData
        );
    });
});
