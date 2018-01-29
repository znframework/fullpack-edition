<!-- OVERVIEW -->
<div class="panel panel-headline">
    <div class="panel-heading">
        <h3 class="panel-title">{{$dict->dashboard}}</h3>
        <p class="panel-subtitle">{{$dict->date}}: {{$current}}</p>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="metric">
                    <span class="icon"><i class="fa fa-user"></i></span>
                    <p>
                        <span class="number">{{$userCount}}</span>
                        <span class="title">{{$dict->users}}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric">
                    <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                    <p>
                        <span class="number">203</span>
                        <span class="title">Sales</span>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric">
                    <span class="icon"><i class="fa fa-eye"></i></span>
                    <p>
                        <span class="number">274,678</span>
                        <span class="title">Visits</span>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric">
                    <span class="icon"><i class="fa fa-bar-chart"></i></span>
                    <p>
                        <span class="number">35%</span>
                        <span class="title">Conversions</span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- TIMELINE -->
        <div class="panel panel-scrolling">
            <div class="panel-heading">
                <h3 class="panel-title">Recent User Activity</h3>
                <div class="right">
                    <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                    <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                </div>
            </div>
            <div class="panel-body">
                <ul class="list-unstyled activity-list">
                    @foreach( $activities as $activity ):
                    <li>
                        <img src="{{photo($activity->photo)}}" alt="Avatar" class="img-circle pull-left avatar">
                        <p><a href="#">{{$activity->name}}</a> {{$activity->activity}} <span class="timestamp">{{$activity->date}}</span></p>
                    </li>
                    @endforeach:
                </ul>
                {{$pagination}}
            </div>
        </div>
        <!-- END TIMELINE -->
    </div>
</div>