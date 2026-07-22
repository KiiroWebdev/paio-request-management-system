<form method="GET" class="mb-3">

    <div class="input-group">

        <span class="input-group-text">

            <i class="bi bi-search"></i>

        </span>

        <input
            type="text"
            class="form-control"
            name="search"
            placeholder="Search..."
            value="{{ request('search') }}">

        <button class="btn btn-outline-secondary">

            Search

        </button>

    </div>

</form>