var Pictures;

function populateCarousel() {
    $("#carousel").empty();
    var userID = $("#userId").val();
    var ownerID = $("#ownerId").val();
    var albumID = $("#albumSelect").val();
    $("#carousel").append("<img src='Contents/img/loading.svg' alt='loading' width='40px' />");
    jQuery.ajax("API/GetAlbumPicturesJson.php?loggedInUserID="+encodeURIComponent(userID)+"&albumUserID="+encodeURIComponent(ownerID)+"&albumID="+encodeURIComponent(albumID))
        .done(function(pictures) {
            $("#carousel").empty();
            Pictures = pictures;
            Array.from(pictures).forEach(function(picture) {
                picture.currentRotation = 0;
                picture.rotateLeft = function() {
                    this.currentRotation += 90;
                };
                picture.rotateRight = function() {
                    this.currentRotation -= 90;
                };
                var thumbnailSrc = "Pictures/" + ownerID + "/" + albumID + "/Thumbnail/" + picture.FileName;
                var originalSrc = "Pictures/" + ownerID + "/" + albumID + "/Original/" + picture.FileName;
                var gallerySrc = "Pictures/" + ownerID + "/" + albumID + "/Gallery/" + picture.FileName;
                $("#carousel").append(`<div class="slide"><img class="thumbnail img-thumbnail" src="${thumbnailSrc}" data-id="${picture.Picture_Id}" data-name="${picture.Title}" data-original-src="${originalSrc}" data-gallery-src="${gallerySrc}" data-thumbnail-src="${thumbnailSrc}" alt="${picture.Title} "/></div>`)
            });
            if (Array.from(pictures).length === 0) {
                $("#carousel").append("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> There are no pictures in the album!</p></div>");
                clearPage();
            }
            $(".thumbnail").first().click();
        });
}

function loadImage(e) {
    // Update current image to clicked image
    var target = $(e.target);
    var displayImage = $("#displayImage");
    displayImage.attr("src", target.attr("data-gallery-src"));
    displayImage.attr("data-id", target.attr("data-id"));
    displayImage.attr("data-name", target.attr("data-name"));
    displayImage.attr("data-original-src", target.attr("data-original-src"));
    displayImage.attr("data-gallery-src", target.attr("data-gallery-src"));
    displayImage.attr("data-thumbnail-src", target.attr("data-thumbnail-src"));
    $("#downloadLink").attr("href", target.attr("data-original-src"));


    var picture = function(target, pictures) {
        return pictures.filter(function(picture) {
            if (picture.Picture_Id.toString() === $(target).attr("data-id")) {
                return picture;
            }
        })[0];
    };

    // Populate other fields
    var userID = $("#userId").val();
    var ownerID = $("#ownerId").val();
    $("#commentsContainer").empty();
    $("#commentsContainer").append("<img src='Contents/img/loading.svg' alt='loading' width='40px' />");
    $.ajax("API/GetPictureCommentsJson.php?loggedInUserID="+encodeURIComponent(userID)+"&albumUserID="+encodeURIComponent(ownerID)+"&pictureID="+encodeURIComponent(target.attr("data-id")))
        .done(function(comments) {
            $("#commentsContainer").empty();
            if (comments.length === 0) {
                $("#commentsContainer").append("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> There are no comments!</p></div>");
            }
            comments.forEach(appendComment);
        });

    //TODO: add comment submission box
    $("#imageTitle").text(target.attr("data-name"));
    $("#description").text(picture(e.target, Pictures).Description);
}

function clearPage() {
    $("#imageTitle").text("");
    $("#description").text("");
    $("#commentsContainer").empty();
    $("#displayImage").attr("src", "https://via.placeholder.com/1024x800?text=No+Images!");
}

//TODO: maybe cache author names so there are less ajax requests made
function appendComment(comment) {
    var container = $("#commentsContainer");
    container.append(`<div class='comment' data-comment-id='${comment.Comment_Id}'><span class='poster distinct'>${comment.authorName} (${comment.Date}): </span><p>${comment.Comment_Text}</p></div>`);
}

function postComment() {
    $.post("API/CommentUtilities.php",
        {
            userID: $("#userId").val(),
            pictureID: $("#displayImage").attr("data-id"),
            comment: $("#commentText").val()
        },
        function(data) {
            if (data) {
                $("#commentText").val("");
                $("#commentsContainer").prepend("<div class='alert alert-success'><p><span class='glyphicon glyphicon-thumbs-up'></span> Comment posted successfully!</p></div>");
            }
            else {
                $("#commentsContainer").prepend("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> An error occurred!</p></div>");
            }
            loadImage({ target: $(`img[data-id='${$("#displayImage").attr("data-id")}']`)[0] }, Pictures);
        });
}

function imageButtonHandler(e) {
    //alert("Preview Image Button Clicked: ");

    // Cache the variables
    var $this = $(e.currentTarget);
    var currentImage = $("#displayImage");
    var downloadLink = $("#downloadLink");

    switch ($this.attr("data-action")) {
        case "rotateLeft":
            currentPicture.rotateLeft();
            var params = [
                "action=rotateLeft",
                "currentRotation=" + currentPicture.currentRotation,
                "filePath=" + encodeURIComponent(currentPicture.albumLink)
            ];

            var url = "http://" + window.location.host + "/API/DisplayPicture.php" + '?' + params.join('&');
            console.log(url);
            console.log(params.join("&"));
            currentImage.attr("src", url);
            //downloadLink.attr("href", url);

            return;
        case "rotateRight":
            currentPicture.rotateRight();
            var params = [
                "action=rotateRight",
                "currentRotation=" + currentPicture.currentRotation,
                "filePath=" + encodeURIComponent(currentPicture.albumLink)
            ];

            var url = "http://" + window.location.host + "/API/DisplayPicture.php" + '?' + params.join('&');
            console.log(url);
            console.log(params.join("&"));
            currentImage.attr("src", url);
            //downloadLink.attr("href", url);

            return;
        case "download": return;
        case "delete":
            var params = [
                "action=delete",
                "filePath=" + encodeURIComponent(currentPicture.thumbLink)
            ];

            window.location.href = "http://" + window.location.host + window.location.pathname + '?' + params.join('&');
            return;
        default: break;
    }
}


//load big image, description, and comments on click

$(document).on("click", ".thumbnail", loadImage);

$(document).on("click", "#submitComment", postComment);

$(document).on("click", ".imageButton", imageButtonHandler);


//populate carousel and default image, then repopulate on change
$(document).on("ready", function() {
    populateCarousel();
});
$(document).on("change", "#albumSelect", function() {
    populateCarousel();
});

