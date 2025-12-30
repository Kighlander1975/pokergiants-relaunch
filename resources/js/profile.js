// resources/js/profile.js
document.addEventListener("DOMContentLoaded", function () {
    // Avatar Display Mode Funktionalität
    window.updateAvatarDisplayMode = function () {
        console.log("updateAvatarDisplayMode called");

        const selectedMode = document.querySelector(
            'input[name="avatar_display_mode"]:checked'
        );
        console.log("selectedMode:", selectedMode);

        if (!selectedMode) {
            alert("Bitte wähle eine Option aus.");
            return;
        }

        // Prüfen ob ein Bild-Avatar vorhanden ist
        const profileContainer = document.querySelector(
            "[data-user-has-avatar]"
        );
        const hasAvatar = profileContainer
            ? profileContainer.getAttribute("data-user-has-avatar") === "true"
            : false;

        if (hasAvatar) {
            alert(
                "Diese Funktion ist nicht verfügbar, wenn ein Bild-Avatar gesetzt ist."
            );
            return;
        }

        const mode = selectedMode.value;
        console.log("mode:", mode);

        // Loading state für den Button
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML =
            '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></svg>Aktualisiere...';
        button.disabled = true;

        fetch("/avatar/display-mode", {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                display_mode: mode,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Erfolg - Seite neu laden um Änderungen zu zeigen
                    location.reload();
                } else {
                    alert(
                        "Fehler beim Aktualisieren: " +
                            (data.message || "Unbekannter Fehler")
                    );
                    // Button zurücksetzen
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch((error) => {
                console.error("Update error:", error);
                alert("Fehler beim Aktualisieren des Avatar-Display-Modus.");
                // Button zurücksetzen
                button.innerHTML = originalText;
                button.disabled = false;
            });
    };

    // Avatar Löschen Funktionalität
    window.deleteAvatar = function () {
        if (
            confirm(
                "Bist du sicher, dass du deinen Avatar löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden."
            )
        ) {
            // Loading state
            const button = event.target.closest("button");
            const originalText = button.innerHTML;
            button.innerHTML =
                '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></svg>Lösche...';
            button.disabled = true;

            fetch("/avatar", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Erfolg - Seite neu laden um Änderungen zu zeigen
                        location.reload();
                    } else {
                        alert(
                            "Fehler beim Löschen: " +
                                (data.message || "Unbekannter Fehler")
                        );
                        // Button zurücksetzen
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch((error) => {
                    console.error("Delete error:", error);
                    alert("Fehler beim Löschen des Avatars.");
                    // Button zurücksetzen
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
    };
});
