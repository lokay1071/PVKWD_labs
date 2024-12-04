import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Category } from './category.entity';
import { DeleteResult, Repository } from 'typeorm';
import {
  IPaginationOptions,
  paginate,
  Pagination,
} from 'nestjs-typeorm-paginate';

@Injectable()
export class CategoriesService {
  constructor(
    @InjectRepository(Category)
    private repository: Repository<Category>,
  ) {}

  public create(categoryData: Category): Promise<Category> {
    return this.repository.save(categoryData);
  }

  public findAll(): Promise<Category[]> {
    return this.repository.find();
  }

  public async findOne(id: number): Promise<Category | null> {
    const category = await this.repository.findOneBy({ id });

    if (!category) {
      throw new NotFoundException(`Category with id ${id} not found.`);
    }

    return category;
  }

  async update(id: number, categoryData: Category): Promise<Category> {
    const category = await this.repository.findOneBy({ id });
    if (!category) {
      throw new Error(`Category with ID ${id} not found.`);
    }

    Object.assign(category, categoryData);
    return this.repository.save(category);
  }

  public async remove(id: number): Promise<DeleteResult> {
    const category = await this.repository.findOneBy({ id });

    if (!category) {
      throw new NotFoundException(`Category with id ${id} not found.`);
    }

    return this.repository.delete(id);
  }

  public paginate(options: IPaginationOptions): Promise<Pagination<Category>> {
    return paginate<Category>(this.repository, options);
  }
}
