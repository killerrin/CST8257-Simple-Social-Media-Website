var Pictures;

function populateCarousel() {
    $("#carousel").empty();
    var userID = $("#userId").val();
    var ownerID = $("#ownerId").val();
    var albumID = $("#albumSelect").val();
    jQuery.ajax("API/GetAlbumPicturesJson.php?loggedInUserID="+encodeURIComponent(userID)+"&albumUserID="+encodeURIComponent(ownerID)+"&albumID="+encodeURIComponent(albumID))
        .done(function(pictures) {
            Pictures = pictures;
            Array.from(pictures).forEach(function(picture) {
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
    $.ajax("API/GetPictureCommentsJson.php?loggedInUserID="+encodeURIComponent(userID)+"&albumUserID="+encodeURIComponent(ownerID)+"&pictureID="+encodeURIComponent(target.attr("data-id")))
        .done(function(comments) {
            $("#commentsContainer").empty();
            if (comments.length === 0) {
                $("#commentsContainer").append("<div class='alert alert-danger'><p><span class='glyphicon glyphicon-thumbs-down'></span> There are no comments!</p></div>");
            }
            comments.sort(function (a, b) { return new Date(a.Date) - (b.Date) });
            comments.map(function (x) { x.authorName = getCommentAuthor(x); return x; });
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
function getCommentAuthor(comment) {
    $.ajax("API/CommentUtilities.php?userID="+comment.Author_Id)
        .done(function(name) {
            return name;
        });
}

function appendComment(comment) {
    var container = $("#commentsContainer");
    container.append(`<div class='comment' data-comment-id='${comment.Comment_Id}'><span class='poster distinct'>${name} (${comment.Date}): </span><p>${comment.Comment_Text}</p></div>`);
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



//load big image, description, and comments on click

$(document).on("click", ".thumbnail", loadImage);

$(document).on("click", "#submitComment", postComment);


//populate carousel and default image, then repopulate on change
$(document).on("ready", function() {
    populateCarousel();
});
$(document).on("change", "#albumSelect", function() {
    populateCarousel();
});

