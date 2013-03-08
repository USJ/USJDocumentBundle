<?php

namespace MDB\DocumentBundle;

final class Events
{
    /**
     * Events call before MDB\DocumentBundle\Document\Documnet persist
     */
    const DOCUMENT_PRE_PERSIST = 'mdb_document.document.pre_persist';

    /**
     * Events call after MDB\DocumentBundle\Document\Documnet persist
     */
    const DOCUMENT_POST_PERSIST = 'mdb_document.document.post_persist';

    /**
     * Events call before MDB\DocumentBundle\Document\Documnet persist
     */
    const DOCUMENT_PRE_LINK = 'mdb_document.document.pre_link';

    /**
     * Events call after MDB\DocumentBundle\Document\Documnet persist
     */
    const DOCUMENT_POST_LINK = 'mdb_document.document.post_link';

    const DOCUMENT_POST_UNLINK = 'mdb_document.document.post_unlink';
}
