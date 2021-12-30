<template>
  <div id="app"></div>
</template>

<script>
const { Deck } = require("@deck.gl/core");

import { MapboxLayer } from "@deck.gl/mapbox";
import { MVTLayer } from "@deck.gl/geo-layers";
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
// import DeckGL from "@deck.gl";
// import { BitmapLayer } from '@deck.gl/layers';
// import { TileLayer } from '@deck.gl/geo-layers';

export default {
  name: "app",
  data() {
    return {
      map: {},
      layer: { }
    };
  },
  methods: {
    init() {
      this.layer = new MVTLayer({
        // data: `https://a.tiles.mapbox.com/v4/mapbox.mapbox-streets-v7/{z}/{x}/{y}.vector.pbf?access_token=${MAPBOX_TOKEN}`,
        data: ["https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}"],
        minZoom: 0,
        maxZoom: 23,
        getLineColor: [192, 192, 192],
        getFillColor: [140, 170, 180],
        id: "my-scatterplot",
        type: "vector",
        source: "my-scatterplot",

        // getLineWidth: (f) => {
        //   switch (f.properties.class) {
        //     case "street":
        //       return 6;
        //     case "motorway":
        //       return 10;
        //     default:
        //       return 1;
        //   }
        // },
        // lineWidthMinPixels: 1,
      });
      this.map.on("load", () => {
        this.map.addSource("my-scatterplot", {
          type: "vector",
          tiles: ["https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}"],
        minZoom: 0,
        maxZoom: 23
        ,
        });

        this.map.addLayer(this.layer, "my-scatterplot");
      });
    },

    deck_geojson_mapboxgl: function () {
      mapboxgl.accessToken =
        "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";

      const map = new mapboxgl.Map({
        container: "app",

        style: "mapbox://styles/mapbox/light-v10",

        // style: 'https://tiles.devsdev.com/style.json',

        center: [-33, 27],
        zoom: 9,
      });

      // const layer = new MVTLayer({
      //     // https://wiki.openstreetmap.org/wiki/Slippy_map_tilenames#Tile_servers
      //     // data: 'https://c.tile.openstreetmap.org/{z}/{x}/{y}.png',
      //     data: "https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}",
      //   id: "my-scatterplot",
      //   type: ScatterplotLayer,
      //     minZoom: 0,
      //     maxZoom: 19,
      //     tileSize: 25

      //     // renderSubLayers: (props) => {
      //     //     const {
      //     //         bbox: { west, south, east, north },
      //     //     } = props.tile;

      //     //     return new BitmapLayer(props, {
      //     //         data: null,
      //     //         image: props.data,
      //     //         bounds: [west, south, east, north],
      //     //     });
      //     // },
      // });

      //  const myDeckLayer =  new MapboxLayer({
      //   mapStyle:
      //     "https://basemaps.cartocdn.com/gl/positron-nolabels-gl-style/style.json",
      //   initialViewState: {
      //     longitude: -122.45,
      //     latitude: 37.8,
      //     zoom: 15,
      //   },
      //   id: "my-scatterplot-2",
      //   controller: true,
      //   layers: [
      //     new ScatterplotLayer({
      //       data: "https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}",
      //       getColor: (d) => d.color,
      //       getRadius: (d) => d.radius,
      //       id: "my-scatterplot-1",
      //       type: ScatterplotLayer,
      //     }),
      //   ],
      // });

      const myDeckLayer = new MapboxLayer({
        id: "my-scatterplot",
        source: "my-scatterplot",
        type: ScatterplotLayer,
        data: "https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}",
        getPosition: (d) => d.position,
        getRadius: (d) => d.size,
        getFillColor: [255, 0, 0],
        getLineColor: [0, 0, 0],
      });

      // map.on("load", () => {
      map.addSource("my-scatterplot", {
        type: "vector",
        tiles: ["https://tiles.devsdev.com/v1/intersects/pup/{z}/{x}/{y}"],
        minzoom: 6,
        maxzoom: 14,
      });

      map.addLayer(myDeckLayer, "my-scatterplot");
      // });
    },
    deckInit: function () {
      this.init();
    },
  },
  mounted() {
    mapboxgl.accessToken =
      "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";

    this.map = new mapboxgl.Map({
      container: "app",
      style: "mapbox://styles/mapbox/light-v10",
      // style: 'https://tiles.devsdev.com/style.json',
      center: [-33, 27],
      zoom: 9,
    });
    this.init();
    // this.deckInit();
  },
  beforeDestroy() {},
};
</script>

<style>
html,
body,
#app {
  margin: 0;
  width: 100%;
  height: 100%;
}
</style>
