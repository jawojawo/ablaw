<style>
    footer {
        font-size: 8px;
        margin-left: 40px;
        margin-right: 40px;
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .text-center {
        text-align: center
    }
</style>

<footer>
    <div>
        Page @pageNumber / @totalPages
    </div>
    <div>
        As of {{ now()->translatedFormat('F j, Y g:i A') }}
    </div>
</footer>
