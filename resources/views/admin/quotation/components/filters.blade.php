<div class="omni-filters">
    <h2>Filters</h2>
    <form action="{{ route('customerquotation.list.admin') }}" method="get">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col-md-3">
                <label for="sales_person" class="normal">Sales Person</label>
                <select name="sales_person" class="form-control" id="sales_person">
                    <option selected="selected" value>Select</option>
                    <option value="#"></option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="customer_id" class="normal">Customer</label>
                <select name="sales_person" class="form-control" id="sales_person">
                    <option selected="selected" value>Select</option>
                    <option value="#"></option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="project" class="normal">Project</label>
                <input type="text" name="project" class="form-control" id="project"
                       value="{{ old('project') }}">
            </div>
            <div class="col-md-2">
                <label for="from" class="normal">From</label>
                <input type="date" name="from" class="form-control" id="from"
                       value="{{ old('from') }}">
            </div>
            <div class="col-md-2">
                <label for="to" class="normal">To</label>
                <input type="date" name="to" class="form-control" id="to"
                       value="{{ old('to') }}">
            </div>
            <div class="col-md-2">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Apply filters</button>
                </div>
            </div>
        </div>
    </form>
</div>
