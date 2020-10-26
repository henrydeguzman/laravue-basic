// import request from '@/utils/request';
import Resource from '@/api/resource';
import request from '@/utils/request';

class ScheduleResource extends Resource {
  constructor(){
    super('schedules');
  }

  personnels() {
    return request({
      url: '/' + this.uri + '/personnels',
      method: 'get',
    });
  }
}

export { ScheduleResource as default };
