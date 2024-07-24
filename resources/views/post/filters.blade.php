<!-- Static Filter Wrap Start -->
<div class="main-card mb-3 card">
    <div class="card-body">
        <form method="POST" id="search-form" class="filterForm form-inline" role="form">
            @csrf

            <div class="form-group">
                <input
                        type="text"
                        name="title"
                        id="title"
                        placeholder="Post Title"
                        class="form-control mr-3"
                />
            </div>

            <div class="form-group">
                <input
                        type="text"
                        name="author_name"
                        id="author_name"
                        placeholder="Author Name"
                        class="form-control mr-3"
                />
            </div>


            <div class="form-group mr-2">
                <label for="status">&nbsp;&nbsp;</label>
                <select class="form-control" name="status" id="status">
                    <option value="">Status</option>
                    @foreach($status as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group filterButtons">
                <button type="submit" class="btn btn-primary filter-col mr-2">Search</button>
                <input type="button" onclick="App.Post.removeFilters();"
                       class="btn btn-primary filter-col mr-2" value="Remove Filters"/>
            </div>

            <div class="form-group" style="padding-left: 5px;">
                <button onclick="App.Helpers.refreshDataTable();" class="btn btn-info" type="button">Refresh</button>
            </div>
        </form>
    </div>
</div>
<!-- Static Filter Wrap End -->