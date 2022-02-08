$(function(){
    $('#add').on('click', (e) => {
        e.preventDefault();

        updateUserAnimeList('/add');
    });

    $('#list-select').on('change', () => {
        let selectedList = $('#selected-list');
        let listKey = $('#list-select option:selected').attr('key');
        
        $('#list-select').removeClass(selectedList.val());
        $('#list-select').addClass(listKey);
        selectedList.val(listKey);

        updateUserAnimeList('/update');
    });

    $('#user-score').on('change', () => {
        updateUserAnimeList('/update');
    });

    $('#number-of-episodes').on('input', () => {
        $('#number-of-episodes').val($('#number-of-episodes').val().replace(/[^0-9]/g, ''));

        let progressEpisodes = parseInt($('#number-of-episodes').val());
        let totalEpisodes = $('#total-episodes').html();

        if(totalEpisodes == '?')
        {
            totalEpisodes = 1000;
        }
        else
        {
            totalEpisodes = parseInt(totalEpisodes);
        } 

        if(progressEpisodes > totalEpisodes)
        {
            $('#number-of-episodes').val(totalEpisodes);
        }

        updateUserAnimeList('/update');
    });

    $('#watch-episodes a').on('click', (e) => {
        e.preventDefault();

        let progressEpisodes = parseInt($('#number-of-episodes').val());
        let totalEpisodes = $('#total-episodes').html();

        if(totalEpisodes == '?')
        {
            totalEpisodes = 1000;
        }
        else
        {
            totalEpisodes = parseInt(totalEpisodes);
        } 

        if(progressEpisodes + 1 <= totalEpisodes)
        {
            $('#number-of-episodes').val(progressEpisodes + 1);
        }

        updateUserAnimeList('/update');
    });

    $('#delete').on('click', (e) => {
        e.preventDefault();

        deleteAnimeFromUserList();
    });
});

async function updateUserAnimeList(url)
{
    let animeId = $('#anime-id').val();
    let listId = $('#list-select').val();
    let score = $('#user-score').val();
    let progressEpisodes = $('#number-of-episodes').val();

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            animeId: animeId,
            listId: listId,
            score: score,
            progressEpisodes: progressEpisodes
        },
        success: (msg) => {
            console.log(msg);

            $('#list-select').show();
            $('#delete').show();
            $('#add').hide();

            $('#user-score').removeAttr('disabled');
            $('#watch-episodes').removeClass('disabled');
            $('#number-of-episodes').removeAttr('disabled');
        },
        error: (msg) => {
            window.location.href = msg.responseJSON;
        },
    });
}

async function deleteAnimeFromUserList()
{
    let animeId = $('#anime-id').val();

    $.ajax({
        url: '/delete',
        method: 'POST',
        data: {
            animeId: animeId
        },
        success: () => {
            location.reload();
        },
        error: (msg) => {
            window.location.href = msg.responseJSON;
        }
    });
}