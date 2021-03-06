var Pictures;
var ReadyToLoad = true;

function populateCarousel() {
    if (!ReadyToLoad) return;
    ReadyToLoad = false;

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
                var thumbnailSrc, originalSrc, gallerySrc;
                picture.thumbnailSrc = thumbnailSrc = "Pictures/" + ownerID + "/" + albumID + "/Thumbnail/" + picture.FileName;
                picture.originalSrc = originalSrc = "Pictures/" + ownerID + "/" + albumID + "/Original/" + picture.FileName;
                picture.gallerySrc = gallerySrc = "Pictures/" + ownerID + "/" + albumID + "/Gallery/" + picture.FileName;
                $("#carousel").append(`<div class="slide"><img class="thumbnail img-thumbnail" src="${thumbnailSrc + "?" +(new Date().getTime())}" data-id="${picture.Picture_Id}" data-name="${picture.Title}" data-original-src="${originalSrc}" data-gallery-src="${gallerySrc}" data-thumbnail-src="${thumbnailSrc}" alt="${picture.Title} "/></div>`)
            });
            if (Array.from(pictures).length === 0) {
                $("#carousel").append("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> There are no pictures in the album!</p></div>");
                clearPage();
            }
            ReadyToLoad = true;
            $(".thumbnail").first().click();
        })
        .fail(function() {
            ReadyToLoad = true;
        });
}

function loadImage(e) {
    if (!ReadyToLoad) return;
    ReadyToLoad = false;

    // Update current image to clicked image
    var target = $(e.target);
    var displayImage = $("#displayImage");
    displayImage.attr("src", target.attr("data-gallery-src") + "?" + (new Date().getTime()));
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
        })
        .always(function() {
            ReadyToLoad = true;
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
    console.log("Posting comment...");
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
                console.log("Comment posted successfully.")
            }
            else {
                $("#commentsContainer").prepend("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> An error occurred!</p></div>");
                console.log("Something went wrong!");
            }
            loadImage({ target: $(`img[data-id='${$("#displayImage").attr("data-id")}']`)[0] }, Pictures);
        });
}

function imageButtonHandler(e) {
    if (!ReadyToLoad) return;
    ReadyToLoad = false;
    //alert("Preview Image Button Clicked: ");

    // Cache the variables
    var $this = $(e.currentTarget);
    var currentImage = $("#displayImage");
    var currentPicture;
    Pictures.forEach(function(picture) {
        if (picture.Picture_Id == currentImage.attr("data-id")) {
            currentPicture = picture;
        }
    });

    if (currentPicture != null) {
        albumLink = "Pictures/"
        switch ($this.attr("data-action")) {
            case "rotateLeft":
                currentPicture.rotateLeft();
                var params = [
                    "action=rotateLeft",
                    "rotation=" + currentPicture.currentRotation,
                    "filePath=" + encodeURIComponent(currentPicture.gallerySrc)
                ];

                var url = "API/DisplayPicture.php" + '?' + params.join('&');
                console.log(url);
                console.log(params.join("&"));
                currentImage.attr("src", url);
                ReadyToLoad = true;

                return;
            case "rotateRight":
                currentPicture.rotateRight();
                var params = [
                    "action=rotateRight",
                    "rotation=" + currentPicture.currentRotation,
                    "filePath=" + encodeURIComponent(currentPicture.gallerySrc)
                ];

                var url = "API/DisplayPicture.php" + '?' + params.join('&');
                console.log(url);
                console.log(params.join("&"));
                currentImage.attr("src", url);
                ReadyToLoad = true;

                return;
            case "download":
                ReadyToLoad = true;
                return;
            case "delete":
                var params = [
                    "action=delete",
                    "pictureID=" + encodeURIComponent(currentPicture.Picture_Id)
                ];

                window.location.href = '?' + params.join('&');
                ReadyToLoad = true;
                return;
            case "save":
                var params = [
                    "action=save",
                    "pictureID=" +encodeURIComponent(currentPicture.Picture_Id),
                    "rotation=" + encodeURIComponent(currentPicture.currentRotation)
                ];

                window.location.href = '?' + params.join('&');
                ReadyToLoad = true;
                return;
            default:
                break;
        }
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

