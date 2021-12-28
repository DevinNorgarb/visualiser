const config = require("../config");
const Tile38 = require("tile38");
const Tile38client = new Tile38(config.tile38);

const GeoJSON = require("geojson");
const geojsonvt = require("geojson-vt");
const vtpbf = require("vt-pbf");

// route schema
const schema = {
  description: "Return table as VT",
  tags: ["feature"],
  summary: "return MVT protobuf",
  params: {
    table: {
      type: "string",
      description: "The name of key.",
    },
    z: {
      type: "number",
      description: "The Z (zoom)",
    },
    x: {
      type: "number",
      description: "The X coord",
    },
    y: {
      type: "number",

      description: "The X coord",
    },
  },
};

module.exports = function (fastify, opts, next) {
  fastify.route({
    method: "GET",
    url: "/intersects/:table/:z/:x/:y",
    schema: schema,
    handler: function (request, reply) {
      console.error(
        request.params.table,
        "/" + request.params.z + "/" + request.params.x + "/" + request.params.y
      );
      let intersects = Tile38client.intersectsQuery(request.params.table)
        .tile(request.params.x, request.params.y, request.params.z)
        .cursor(1000)
        .limit(1000000);
      intersects
        .execute()
        .then((results) => {
          if (results.count && results.count > 0) {
            const res = GeoJSON.parse(results.objects, { GeoJSON: "object" });

            var tileOptions = {
              maxZoom: 24,
              minZoom: 1,
              tolerance: 4,
              extent: 4096, // tile extent (both width and height)
              buffer: 64,
              debug: 0,
              indexMaxZoom: 24,
              indexMinZoom: 1,
              indexMaxPoints: 1000000,
              solidChildren: false, // whether to include solid tile children in the index
            };

            const tileIndex = geojsonvt(res, tileOptions);
            const tile = tileIndex.getTile(
              request.params.z,
              request.params.x,
              request.params.y
            );
            const buffer = Buffer.from(
              vtpbf.fromGeojsonVt({ geojsonLayer: tile })
            );
            reply.type("application/protobuf").send(buffer);
          } else {
            reply.type("application/protobuf").send();
          }
        })
        .catch((err) => {
          console.error("something went wrong! " + err);
        });
    },
  });
  next();
};

module.exports.autoPrefix = "/v1";
