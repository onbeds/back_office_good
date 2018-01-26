@extends('templates.dash')

@section('titulo','Redes Referidos')

@section('content')

        <section class="invoice">
            <div class="page-header no-breadcrumb font-header"><i class="fa fa-user"></i> ¡Bienvenido {{currentUser()->nombres}}!</div>
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default bg-info panel-stat no-icon">
                                <div class="panel-body content-wrap">
                                    <div class="value">
                                        <h2 class="font-header no-m"> {{$send['referidos']}}</h2>
                                    </div>
                                    <div class="detail text-right">
                                        <div class="text-upper">Referidos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default bg-success panel-stat no-icon">
                                <div class="panel-body content-wrap">
                                    <div class="value">
                                        <h2 class="font-header no-m">{{$send['orders']}}</h2>
                                    </div>
                                    <div class="detail text-right">
                                        <div class="text-upper">Compras</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default bg-purple panel-stat no-icon">
                                <div class="panel-body content-wrap">
                                    <div class="value">
                                        <h2 class="font-header no-m">{{"$" . $send['total']}}</h2>
                                    </div>
                                    <div class="detail text-right">
                                        <div class="text-upper">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="panel panel-default">
                    <div class="panel-heading font-header">Diagrama de redes</div>
                    <div class="panel-body">

                        <div id="container">

                            <div class="row">
                                <div id="left-container" class="hidden col-md-1">
                                    <div class="text">
                                        <h4>Tree Animation</h4>
                                    </div>
                                    <div id="id-list">

                                    </div>
                                </div>

                                <div id="center-container"  style="background-color: transparent">
                                    <div id="infovis" class="embed-responsive">

                                    </div>
                                </div>

                                <div id="right-container" class="hidden col-md-1">

                                    <h4>Tree Orientation</h4>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="r-left">Left </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="r-left" name="orientation" checked="checked" value="left" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="r-top">Top </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="r-top" name="orientation" value="top" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="r-bottom">Bottom </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="r-bottom" name="orientation" value="bottom" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="r-right">Right </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="r-right" name="orientation" value="right" />
                                            </td>
                                        </tr>
                                    </table>

                                    <h4>Selection Mode</h4>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="s-normal">Normal </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="s-normal" name="selection" checked="checked" value="normal" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="s-root">Set as Root </label>
                                            </td>
                                            <td>
                                                <input type="radio" id="s-root" name="selection" value="root" />
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                                <div id="log"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>


@endsection

@section('scripts')
    <script>
        var labelType, useGradients, nativeTextSupport, animate;
        (function() {
            var ua = navigator.userAgent,
                iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
                typeOfCanvas = typeof HTMLCanvasElement,
                nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
                textSupport = nativeCanvasSupport
                    && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
            labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
            nativeTextSupport = labelType == 'Native';
            useGradients = nativeCanvasSupport;
            animate = !(iStuff || !nativeCanvasSupport);
        })();
        var Log = {
            elem: false,
            write: function(text){
                if (!this.elem)
                    this.elem = document.getElementById('log');
                this.elem.innerHTML = text;
                this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
            }
        };
        function init(){
            //init data
            var json = {
                id: "{!! $send['tercero']['id'] !!}",
                name: "{!! $send['tercero']['nombres'] !!}",
                data: {},
                children: [
                        @foreach($send['redes'] as $red)
                    {
                        id: "{!! $red['id'] !!}",
                        name: "{!! $red['name'] !!}",
                        children:  [
                                @foreach($send['terceros']  as $tercero)
                                @if ($tercero['network_id'] == $red['id'] && $send['tercero']['email'] == $tercero['apellidos'])
                            {
                                id: "{!! $tercero['id'] !!}",
                                name: "{!! $tercero['id'] !!}",
                                children: [
                                ]
                            },
                            @endif
                            @endforeach
                        ]
                    },
                    @endforeach
                ]
            };
            //end
            //init Spacetree
            //Create a new ST instance
            var st = new $jit.ST({
                //id of viz container element
                injectInto: 'infovis',
                //set duration for the animation
                duration: 800,
                //set animation transition type
                transition: $jit.Trans.Quart.easeInOut,
                //set distance between node and its children
                levelDistance: 80,
                //enable panning
                Navigation: {
                    enable:true,
                    panning:true
                },
                //set node and edge styles
                //set overridable=true for styling individual
                //nodes or edges
                Node: {
                    height: 30,
                    width: 200,
                    type: 'rectangle',
                    color: '#aaa',
                    overridable: true
                },
                Edge: {
                    type: 'bezier',
                    overridable: true
                },
                onBeforeCompute: function(node){
                    Log.write("Cargando su información...");
                },
                onAfterCompute: function(){
                    Log.write("¡Listo!");
                },
                //This method is called on DOM label creation.
                //Use this method to add event handlers and styles to
                //your node.
                onCreateLabel: function(label, node){
                    label.id = node.id;
                    label.innerHTML = node.name;
                    label.onclick = function(){
                        if(normal.checked) {
                            st.onClick(node.id);
                        } else {
                            st.setRoot(node.id, 'animate');
                        }
                    };
                    //set label styles
                    var style = label.style;
                    style.width = 120 + 'px';
                    style.height = 30 + 'px';
                    style.cursor = 'pointer';
                    style.color = 'white';
                    style.fontSize = '0.8em';
                    style.textAlign= 'left';
                    style.paddingTop = '7px';
                    style.marginLeft = '15px';
                },
                //This method is called right before plotting
                //a node. It's useful for changing an individual node
                //style properties before plotting it.
                //The data properties prefixed with a dollar
                //sign will override the global node style properties.
                onBeforePlotNode: function(node){
                    //add some color to the nodes in the path between the
                    //root node and the selected node.
                    if (node.selected) {
                        node.data.$color = "#f60620";
                    }
                    else {
                        delete node.data.$color;
                        //if the node belongs to the last plotted level
                        if(!node.anySubnode("exist")) {
                            //count children number
                            var count = 0;
                            node.eachSubnode(function(n) { count++; });
                            //assign a node color based on
                            //how many children it has
                            node.data.$color = ['#aaa', '#baa', '#caa', '#daa', '#eaa', '#faa'][count];
                        }
                    }
                },
                //This method is called right before plotting
                //an edge. It's useful for changing an individual edge
                //style properties before plotting it.
                //Edge data proprties prefixed with a dollar sign will
                //override the Edge global style properties.
                onBeforePlotLine: function(adj){
                    if (adj.nodeFrom.selected && adj.nodeTo.selected) {
                        adj.data.$color = "white";
                        adj.data.$lineWidth = 100;
                    }
                    else {
                        delete adj.data.$color;
                        delete adj.data.$lineWidth;
                    }
                }
            });
            //load json data
            st.loadJSON(json);
            //compute node positions and layout
            st.compute();
            //optional: make a translation of the tree
            st.geom.translate(new $jit.Complex(-100, 0), "current");
            //emulate a click on the root node.
            st.onClick(st.root);
            //end
            //Add event handlers to switch spacetree orientation.
            var top = $jit.id('r-top'),
                left = $jit.id('r-left'),
                bottom = $jit.id('r-bottom'),
                right = $jit.id('r-right'),
                normal = $jit.id('s-normal');
            function changeHandler() {
                if(this.checked) {
                    top.disabled = bottom.disabled = right.disabled = left.disabled = true;
                    st.switchPosition(this.value, "animate", {
                        onComplete: function(){
                            top.disabled = bottom.disabled = right.disabled = left.disabled = false;
                        }
                    });
                }
            };
            top.onchange = left.onchange = bottom.onchange = right.onchange = changeHandler;
            //end
            $( "#left-container" ).remove();
            $( "#right-container" ).remove();
            $( "#log" ).remove();
        }
        init();
    </script>
@stop