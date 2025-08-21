// Ce fichier est maintenu par ESSARRAJ Fouad

import { Exception } from "sass";
import { Action } from "../../actions/Action";
import { AjaxErrorHandler } from "../AjaxErrorHandler";

/**
 * MetaCache
 * - Clé = (entityType, id, field)
 * - Stockage en mémoire avec TTL
 * - Utilisé par CellOrchestrator pour charger rapidement les metas
 */
export class MetaCache extends Action {
    constructor(config) {
        super(config);
        this.config = config;
        this.cache = new Map(); // clé -> { meta, expire }
        this.ttl = 5 * 60 * 1000; // 5 minutes
    }

    _key(entityType, id, field) {
        return `${entityType}:${id}:${field}`;
    }

    /**
     * Retourne une meta si présente et valide
     */
    get(entityType, id, field) {
        const key = this._key(entityType, id, field);
        const entry = this.cache.get(key);
        if (entry && entry.expire > Date.now()) {
            return entry.meta;
        }
        return null;
    }

    /**
     * Définit une meta dans le cache
     */
    set(entityType, id, field, meta) {
        const key = this._key(entityType, id, field);
        this.cache.set(key, {
            meta,
            expire: Date.now() + this.ttl,
        });
    }

     /**
     * Récupère une meta (cache ou API) avec gestion d’erreur via AjaxErrorHandler.
     */
   async getMeta(entityType, id, field) {
        const cached = this.get(entityType, id, field);
        if (cached) return cached;

        return new Promise((resolve, reject) => {

            let fieldMetaUrl = this.config.fieldMetaUrl.replace(':id', id);
            fieldMetaUrl = fieldMetaUrl.replace(':field', field);

            $.ajax({
                url: fieldMetaUrl,
                method: 'GET',
                dataType: 'json'
            })
            .done((meta) => {
                this.set(entityType, id, field, meta);
                resolve(meta);
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                if (jqXHR.status) {
                    // ⚠️ Erreur HTTP (404, 500, etc.)
                    //AjaxErrorHandler.handleError(jqXHR, "Erreur lors de la récupération des métadonnées.");
                   reject(jqXHR);
                } else {
                    // ⚠️ Erreur réseau (ex: pas de connexion)
                    //AjaxErrorHandler.handleError(errorThrown, "Erreur réseau lors de la récupération des métadonnées.");
                     reject(jqXHR);
                }
            });
        });
    }
}

 