function confirmDeleteCard(cardId, cardName) {
    console.log("CardId: " + cardId);
    console.log("Card Name: " + cardName);

    // Show the SweetAlert2 confirmation dialog.
    Swal.fire({
        title: "Are you sure?",
        text: "Please confirm deleting card: " + cardName,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Navigate to the GET delete route after confirmation
            window.location.href =
                APP_BASE_URL + "/cards/" + cardId + "/delete";
        }
    });
}
