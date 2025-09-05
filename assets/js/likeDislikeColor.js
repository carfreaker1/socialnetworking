document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('#feed').addEventListener('click', function (e) {
        if (e.target.classList.contains('like') || e.target.classList.contains('dislike')) {
            const post = e.target.closest('.post'); 
            const likeBtn = post.querySelector('.like');
            const dislikeBtn = post.querySelector('.dislike');
            const isLike = e.target.classList.contains('like');
            const liked = likeBtn.classList.contains('liked');
            const disliked = dislikeBtn.classList.contains('disliked');

            if (isLike) {
                likeBtn.classList.toggle('liked');
                if (liked) {
                    dislikeBtn.classList.remove('disliked');
                } else if (disliked) {
                    dislikeBtn.classList.remove('disliked');
                }
            } else {
                dislikeBtn.classList.toggle('disliked');
                if (disliked) {
                    likeBtn.classList.remove('liked');
                } else if (liked) {
                    likeBtn.classList.remove('liked');
                }
            }
        }
    });
});