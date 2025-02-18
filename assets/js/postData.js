/*
const baseUrl = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
const apiUrl = `${baseUrl}/api/api_gateway.php`;
*/
/**
 * Helper function to send POST requests.
 * @param {string} url - The endpoint URL.
 * @param {Object} data - The data to send in the request body.
 * @returns {Promise<Object>} - The JSON response.
 */
/*
async function postData(url, data) {
    try {
        // Récupérer le token stocké dans localStorage
        let token = localStorage.getItem('authToken');
        
        // Si le token n'existe pas, essayez de le récupérer à partir de la session.
        if (!token) {
            console.log('Token non trouvé, vérifiez si l\'utilisateur est authentifié.');
            // Vous pourriez rediriger l'utilisateur vers la page de connexion ici
            throw new Error('Utilisateur non authentifié.');
        }

        // Préparer l'en-tête
        const headers = {
            "Content-Type": "application/json",
        };

        // Si un token est fourni, l'ajouter dans l'en-tête Authorization
        if (token) {
            headers["Authorization"] = `Bearer ${token}`;
        }

        const response = await fetch(url, {
            method: "POST",
            headers: headers,
            body: JSON.stringify(data),
        });
        
        if (!response.ok) throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
        
        const jsonResponse = await response.json();
        console.log('Réponse API:', jsonResponse);
        
        return jsonResponse;
    } catch (error) {
        console.error("Erreur :", error);
        return { error: error.message };
    }
}
*/


const baseUrl = `${window.location.protocol}//${window.location.host}`;
const apiUrl = `${baseUrl}/api/api_gateway.php`;
/**
 * Helper function to send POST requests.
 * @param {string} url - The endpoint URL.
 * @param {Object} data - The data to send in the request body.
 * @returns {Promise<Object>} - The JSON response.
 */
async function postData(url, data) {
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        
        const jsonResponse = await response.json();
        console.log('API Response:', jsonResponse);
        if (jsonResponse.status === 'success') {
            // Gérer la redirection en fonction de la réponse
            if (jsonResponse.data.redirect) {
                window.location.href = jsonResponse.data.redirect;
            } else {
                // Redirection par défaut si aucune redirection n'est spécifiée
                window.location.href = '/';
            }
        }
        if (jsonResponse.error) {
            if (jsonResponse.isAdmin) {
                // Afficher l'erreur dans l'interface admin
                console.error(jsonResponse.error);
                throw new Error(jsonResponse.error);
            } else {
                // Rediriger vers la page d'erreur pour les utilisateurs normaux
                window.location.href = `/error?code=${encodeURIComponent(jsonResponse.code)}&message=${encodeURIComponent(jsonResponse.error)}`;
            }
        }
        
        return jsonResponse;
    } catch (error) {
        console.error("Error:", error);
        return { error: error.message };
    }
}