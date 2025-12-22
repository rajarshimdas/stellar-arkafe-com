<style>
    #navbox {
        background-color: cadetblue;
        line-height: 65px;
        text-align: center;
        color: white;
        width: 100%;
        max-width: var(--rd-max-width);
        margin: auto;
        font-family: 'Roboto Bold';
    }

    #navbox button {
        margin: 5px;
        padding: 2px 20px;
        border-radius: 50px;
        border: 3px solid white;
        font-family: 'Roboto Bold';
        color: gray;
    }

    #navbox button.active {
        color: var(--rd-nav-dark);
        border: 3px solid var(--rd-nav-dark);
    }

    #navbox button:hover {
        background-color: var(--rd-nav-dark);
        border: 3px solid var(--rd-nav-dark);
        color: white;
        cursor: pointer;
    }
</style>