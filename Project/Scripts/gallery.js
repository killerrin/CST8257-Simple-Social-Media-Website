function populateCarousel() {
    var userID = $("#userId").val();
    var ownerID = $("#ownerId").val();
    var albumID = $("#albumSelect").val();
    jQuery.ajax("API/GetAlbumPicturesJson.php?loggedInUserID="+encodeURIComponent(userID)+"&albumUserID="+encodeURIComponent(ownerID)+"&albumID="+encodeURIComponent(albumID))
        .done(function(pictures) {
            Array.from(pictures).forEach(function(picture) {
                var thumbnailSrc = "Pictures/" + ownerID + "/" + albumID + "/Thumbnail/" + picture.FileName;
                var originalSrc = "Pictures/" + ownerID + "/" + albumID + "/Original/" + picture.FileName;
                var gallerySrc = "Pictures/" + ownerID + "/" + albumID + "/Gallery/" + picture.FileName;
                $("#carousel").append(`<div class="slide"><img class="godDamnedPicture" src="${thumbnailSrc}" data-id="${picture.Picture_Id}" data-name="${picture.Title}" data-original-src="${originalSrc}" data-gallery-src="${gallerySrc}" data-thumbnail-src="${thumbnailSrc}" alt="${picture.Title} "/></div>`)
            });
        });
}

function loadImage(e) {
    // Update current image to clicked image
    var displayImage = $("#displayImage");
    displayImage.attr("src", $(e.target).attr("data-gallery-src"));
    displayImage.attr("data-id", $(e.target).attr("data-id"));
    displayImage.attr("data-name", $(e.target).attr("data-name"));
    displayImage.attr("data-original-src", $(e.target).attr("data-original-src"));
    displayImage.attr("data-gallery-src", $(e.target).attr("data-gallery-src"));
    displayImage.attr("data-thumbnail-src", $(e.target).attr("data-thumbnail-src"));

    // Populate other fields


    $("#imageTitle").text($(e.target).attr("data-name"));
    $("#description").text();
}

//load big image, description, and comments

$(document).on("click", ".godDamnedPicture", loadImage);

//TODO: Somehow trigger loadImage on page load....... I don't like jQuery.........
$(document).find(".godDamnedPicture").click();

//populate carousel and default image, then repopulate on change
$(document).on("ready", function() {
    populateCarousel();
});
$(document).on("change", "#albumSelect", function() {
    populateCarousel();
});

