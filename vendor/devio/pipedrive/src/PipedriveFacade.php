<?php

namespace Devio\Pipedrive;

use Illuminate\Support\Facades\Facade;

/**
 * @method static Resources\Activities activities()
 * @method static Resources\ActivityFields activityFields()
 * @method static Resources\ActivityTypes activityTypes()
 * @method static Resources\Authorizations authorizations()
 * @method static Resources\Currencies currencies()
 * @method static Resources\DealFields dealFields()
 * @method static Resources\Deals deals()
 * @method static Resources\EmailMessages emailMessages()
 * @method static Resources\EmailThreads emailThreads()
 * @method static Resources\Files files()
 * @method static Resources\Filters filters()
 * @method static Resources\GlobalMessages globalMessages()
 * @method static Resources\Goals goals()
 * @method static Resources\NoteFields noteFields()
 * @method static Resources\Notes notes()
 * @method static Resources\OrganizationFields organizationFields()
 * @method static Resources\OrganizationRelationships organizationRelationships()
 * @method static Resources\Organizations organizations()
 * @method static Resources\PermissionSets permissionSets()
 * @method static Resources\PersonFields personFields()
 * @method static Resources\Persons persons()
 * @method static Resources\Pipelines pipelines()
 * @method static Resources\ProductFields productFields()
 * @method static Resources\Products products()
 * @method static Resources\PushNotifications pushNotifications()
 * @method static Resources\Recents recents()
 * @method static Resources\Roles roles()
 * @method static Resources\SearchResults searchResults()
 * @method static Resources\Stages stages()
 * @method static Resources\UserConnections userConnections()
 * @method static Resources\Users users()
 * @method static Resources\UserSettings userSettings()
 */
class PipedriveFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pipedrive';
    }
}
