$(function(){
    $('#add').on('click', (e) => {
        e.preventDefault();

        $('#list-select').show();
        $('#delete').show();
        $('#add').hide();

        $('#user-score').removeAttr('disabled');
        $('#watch-episodes').removeClass('disabled');
        $('#number-of-episodes').removeAttr('disabled');

        updateUserAnimeList('insert');
    });

    $('#list-select').on('change', () => {
        let selectedList = $('#selected-list');
        let listKey = $('#list-select option:selected').attr('key');
        
        $('#list-select').removeClass(selectedList.val());
        $('#list-select').addClass(listKey);
        selectedList.val(listKey);

        updateUserAnimeList('update');
    });

    $('#user-score').on('change', () => {
        updateUserAnimeList('update');
    });

    $('#number-of-episodes').on('input', () => {
        $('#number-of-episodes').val($('#number-of-episodes').val().replace(/[^0-9]/g, ''));

        let progressEpisodes = parseInt($('#number-of-episodes').val());
        let totalEpisodes = parseInt($('#total-episodes').html());

        if(totalEpisodes == '?')
        {
            totalEpisodes = 1000;
        }

        if(progressEpisodes > totalEpisodes)
        {
            $('#number-of-episodes').val(totalEpisodes);
        }

        updateUserAnimeList('update');
    });

    $('#watch-episodes a').on('click', (e) => {
        e.preventDefault();

        let progressEpisodes = parseInt($('#number-of-episodes').val());
        let totalEpisodes = parseInt($('#total-episodes').html());

        if(totalEpisodes == '?')
        {
            totalEpisodes = 1000;
        }

        if(progressEpisodes + 1 <= totalEpisodes)
        {
            $('#number-of-episodes').val(progressEpisodes + 1);
        }

        updateUserAnimeList('update');
    });

    $('#delete').on('click', (e) => {
        e.preventDefault();

        deleteAnimeFromUserList();
    });
});

async function updateUserAnimeList(type)
{
    let username = $('#username').html();

    if(username === undefined)
    {
        window.location.href = 'index.php?a=login';
    }
    else
    {
        let animeId = $('#anime-id').val();
        let listId = $('#list-select').val();
        let score = $('#user-score').val();
        let progressEpisodes = $('#number-of-episodes').val();

        $.ajax({
            url: 'index.php?a=update',
            method: 'POST',
            data: {
                username: username,
                animeId: animeId,
                listId: listId,
                score: score,
                progressEpisodes: progressEpisodes,
                type: type
            },
            success: (msg) => {
                console.log(msg);
            },
            error: (msg) => {
                console.log(msg);
            }
        });
    }
}

async function deleteAnimeFromUserList()
{
    let username = $('#username').html();

    if(username === undefined)
    {
        window.location.href = 'index.php?a=login';
    }
    else
    {
        let animeId = $('#anime-id').val();

        $.ajax({
            url: 'index.php?a=delete',
            method: 'POST',
            data: {
                username: username,
                animeId: animeId
            },
            success: () => {
                location.reload();
            },
            error: () => {
                
            }
        });
    }
}