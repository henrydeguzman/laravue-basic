import Resource from '@/api/resource';

class PersonnelResource extends Resource {
  constructor(){
    super('personnels');
  }
}

export { PersonnelResource as default };
