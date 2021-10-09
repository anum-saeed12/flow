<div class="omni-filters">
    <h2>Filters</h2>
    <form action="{{ route('sale.list.client') }}" method="get">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col-md-3 ">
                <label for="sale_person" class="normal">Sales Person</label>
                <select name="sale_person" class="form-control" id="sale_person">
                    <option selected="selected" value>Select</option>
                    <option value="#"></option>
                </select>
            </div>
            <div class="col-md-2 ">
                <label for="customer_name" class="normal">Customer</label>
                <select name="customer_name" class="form-control" id="customer_name">
                    <option selected="selected" value>Select</option>
                    <option value="#"></option>
                </select>
            </div>
            <div class="col-md-2 ">
                <label for="project" class="normal">Project</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="project" min="0" name="project" class="form-control" placeholder="Project Name" autocomplete="off" aria-autocomplete="off" value="{{ request('amount_max') }}"/>
                </div>
            </div>
            <div class="col-md-2 ">
                <label for="project" class="normal">From</label>
                <div class="input-group input-group-sm">
                    <input type="date" id="project" min="0" name="project" class="form-control" placeholder="Project Namr" autocomplete="off" aria-autocomplete="off" value="{{ request('amount_max') }}"/>
                </div>
            </div>
            <div class="col-md-2 ">
                <label for="project" class="normal">To</label>
                <div class="input-group input-group-sm">
                    <input type="date" id="project" min="0" name="project" class="form-control" placeholder="Project Namr" autocomplete="off" aria-autocomplete="off" value="{{ request('amount_max') }}"/>
                </div>
            </div>
            <div class="col-md-2 ">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Apply filters</button>
                </div>
            </div>
        </div>
    </form>
</div>
